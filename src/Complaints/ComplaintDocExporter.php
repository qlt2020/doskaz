<?php


namespace App\Complaints;

use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ComplaintDocExporter implements ComplaintExporter
{
    private string $templatePath;

    private Connection $connection;
    private string $storagePath;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    public function __construct(string $templatePath, string $storagePath, Connection $connection, TranslatorInterface $translator)
    {
        $this->templatePath = $templatePath;
        $this->connection = $connection;
        $this->storagePath = $storagePath;
        $this->translator = $translator;
    }

    public function execute(int $id, string $locale): \SplFileObject
    {
        Carbon::setLocale($locale === 'kz' ? 'kk' : 'ru');

        $complaintData = $this->connection->createQueryBuilder()
            ->addSelect('complaint_authorities.name as "authorityName"')
            ->addSelect('complainant')
            ->addSelect('content')
            ->addSelect('complainant_cities.name as "complainantCityName"')
            ->addSelect('complaint_cities.name as "complaintCityName"')
            ->addSelect('complaints.created_at')
            ->from('complaints')
            ->join('complaints', 'cities', 'complainant_cities', 'complainant_cities.id = (complaints.complainant->>\'cityId\')::INT')
            ->join('complaints', 'cities', 'complaint_cities', 'complaint_cities.id = (complaints.content->>\'cityId\')::INT')
            ->join('complaints', 'complaint_authorities', 'complaint_authorities', 'complaints.authority_id = complaint_authorities.id')
            ->andWhere('complaints.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$complaintData) {
            throw new NotFoundHttpException();
        }

        /**
         * @var $content ComplaintContent | ComplaintType1 | ComplaintType2
         */
        $content = $this->connection->convertToPHPValue($complaintData['content'], ComplaintContent::class);

        /**
         * @var $complainant Complainant
         */
        $complainant = $this->connection->convertToPHPValue($complaintData['complainant'], Complainant::class);

        $data = [
            'authorityName' => $complaintData['authorityName'],
            'complainantName' => $complainant->lastName . ' ' . $complainant->firstName . ' ' . $complainant->middleName,
            'IIN' => $complainant->iin,
            'complainantCity' => $complaintData['complainantCityName'],
            'complainantStreet' => $complainant->street,
            'complainantBuilding' => $complainant->building,
            'complainantApartment' => $complainant->apartment,
            'complainantPhone' => $complainant->phone,
            'visitedDate' => Carbon::instance($content->visitedAt)->isoFormat("D MMMM YYYY"),
            'objectName' => $content->objectName,
            'objectCity' => $complaintData['complaintCityName'],
            'objectStreet' => $content->street,
            'objectBuilding' => $content->building,
            'visitPurpose' => $content->visitPurpose,
            'complaintDate' => Carbon::instance($this->connection->convertToPHPValue($complaintData['created_at'], 'datetimetz_immutable'))->isoFormat("D MMMM YYYY")
        ];

        $templateProcessor = new TemplateProcessor($this->templatePath);
        $templateProcessor->setValues($data);

        if ($content instanceof ComplaintType2) {
            $x = array_map(function ($attr) use ($content) {
                return [
                    'title' => $attr['title'],
                    'options' => array_filter($attr['options'], function ($option) use ($content) {
                        return in_array($option['key'], $content->options);
                    })
                ];
            }, ComplaintAttributes::$attributes);

            $attributes = array_filter($x, function ($item) {
                return count($item['options']);
            });

            $c = function () use ($attributes, $content) {
                foreach ($attributes as $attribute) {
                    foreach ($attribute['options'] as $option) {
                        yield ['violationTitle' => $attribute['title'] . ': ' . $option['label']];
                    }
                }
                if ($content->comment) {
                    yield ['violationTitle' => $content->comment];
                }
            };

            $templateProcessor->cloneBlock('violations', 0, true, false, iterator_to_array($c()));

            if ($content->threatToLife) {
                $templateProcessor->cloneBlock(
                    'threatToLife',
                    0,
                    true,
                    false,
                    [
                        [
                            'threatToLifeText' => 'Данными условиями создана угроза причинения вреда моей жизни и здоровью.'
                        ]
                    ]
                );
            } else {
                $templateProcessor->cloneBlock('threatToLife', 0, true);
            }
        } elseif ($content instanceof ComplaintType1) {
            $templateProcessor->cloneBlock('threatToLife', 0, true);
            $templateProcessor->cloneBlock('violations', 0, true, false, [
                ['violationTitle' => 'отсутствие пандуса/подъемника не позволило мне посетить данный объект.'],
            ]);
        }

        $photos = array_values(array_filter($content->photos, function ($photo) {
            return !empty($photo);
        }));

        $templateProcessor->cloneBlock('photos', count($photos), true, true);

        foreach ($photos as $index => $photo) {
            $templateProcessor->setImageValue('photo#' . ($index + 1), [
                'path' => $this->storagePath . $photo,
                'width' => '15cm',
                'height' => '',
                'ratio' => true
            ]);
        }

        $templateProcessor->cloneBlock('links', 0, true, false, array_map(function ($item) {
            return ['link' => $item];
        }, array_filter($content->videos, function ($item) {
            return !empty($item);
        })));

        $path = tempnam('/tmp', 'complaint');
        $templateProcessor->saveAs($path);
        return new \SplFileObject($path);
    }
}
