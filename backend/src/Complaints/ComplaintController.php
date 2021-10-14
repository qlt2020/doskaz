<?php
declare(strict_types=1);

namespace App\Complaints;

use App\Cities\Cities;
use App\Cities\FindCityIdByLocation;
use App\Complaints\Services\ExportToExcelService;
use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObject;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use PhpOffice\PhpWord\TemplateProcessor;
use Safe\Exceptions\FilesystemException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\OfficeRequest;
use TheCodingMachine\Gotenberg\RequestException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route(path="/api/complaints")
 */
final class ComplaintController extends AbstractController
{
    private $projectDir;

    /**
     * @IsGranted("ROLE_USER")
     * ComplaintController constructor.
     * @param $projectDir
     */
    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }


    /**
     * @Route(path="/validate", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param ComplaintData $complaintData
     */
    /*
     * @Post(path="/api/complaints/validate",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     summary="Валидация жалобы",
     *     @RequestBody(required=true, description="Жалоба", @JsonContent(ref="#/components/schemas/Complaint")),
     *     responses={
     *         @Response(response="204", description="No content"),
     *         @Response(response="401", description="Not authorized"),
     *         @Response(response="400", description="Bad content")
     * })
     */
    public function validate(ComplaintData $complaintData)
    {
    }

    /**
     * @Route(methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param ComplaintData $complaintData
     * @param ComplaintRepository $complaintRepository
     * @param Flusher $flusher
     * @Post(path="/api/complaints",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     summary="Подача жалобы",
     *     @RequestBody(required=true, description="Жалоба", @JsonContent(ref="#/components/schemas/Complaint")),
     *     responses={
     *         @Response(response="204", description="No content"),
     *         @Response(response="401", description="Not authorized"),
     *         @Response(response="400", description="Bad content")
     * })
     */
    public function make(ComplaintData $complaintData, ComplaintRepository $complaintRepository, Flusher $flusher)
    {
        $complaint = new Complaint(
            $complaintData->complainant,
            $complaintData->content,
            $complaintData->authorityId,
            $this->getUser()->id(),
            $complaintData->objectId == 0 ? null : $complaintData->objectId,
            $complaintData->rememberPersonalData
        );
        $complaintRepository->add($complaint);
        $flusher->flush();

        return [
            'id' => $complaint->id()
        ];
    }

    /**
     * @Route(path="/authorities", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @param Connection $connection
     * @return mixed[]
     * @Get(
     *     path="/api/complaints/authorities",
     *     summary="Список органов обращения",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="id", type="integer"),
     *             @Property(property="name", type="string"),
     *         )
     *     )
     * )
     */
    public function complaintAuthorities(Connection $connection)
    {
        return $connection->createQueryBuilder()
            ->select('id', 'name')
            ->from('complaint_authorities')
            ->where('deleted_at IS NULL')
            ->orderBy('id', 'desc')
            ->execute()
            ->fetchAll();
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return mixed[]
     */
    public function list(Request $request, Connection $connection)
    {
        $complaintsQuery = $connection->createQueryBuilder()
            ->select('id', 'created_at as "createdAt"', 'concat(complaints.complainant->>\'lastName\', \' \', complaints.complainant->>\'firstName\', \' \', complaints.complainant->>\'middleName\') as "fullName"')
            ->from('complaints');

        return [
            'items' => array_map(function ($complaint) use ($connection) {
                return array_replace($complaint, [
                    'createdAt' => $connection->convertToPHPValue($complaint['createdAt'], 'datetimetz_immutable')
                ]);
            }, (clone $complaintsQuery)->orderBy('id', 'desc')->setMaxResults($request->query->getInt('limit', 20))
                ->setFirstResult($request->query->getInt('offset', 0))
                ->execute()->fetchAll()),
            'count' => $complaintsQuery->select('COUNT(*)')->execute()->fetchColumn()
        ];
    }

    /**
     * @Route(path="/{id}/pdf", methods={"GET"}, requirements={"id" = "\d+"})
     * @IsGranted("ROLE_USER")
     * @param $id
     * @param ComplaintPdfExporter $complaintPdfExporter
     * @param Request $request
     * @return BinaryFileResponse
     * @Get(
     *     path="/api/complaints/{id}/pdf",
     *     tags={"Жалобы"},
     *     summary="Экспорт в pdf",
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="id", in="path", required=true, description="Id жалобы", @Schema(type="integer")),
     *     @Parameter(name="locale", in="query", description="Язык", @Schema(type="string", enum={"ru", "kz"})),
     *     @Response(response=200, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=403, description=""),
     *     @Response(response=404, description=""),
     * )
     */
    public function pdfExport($id, ComplaintPdfExporter $complaintPdfExporter, Request $request)
    {
        $this->denyAccessUnlessGranted(ComplaintPermissions::PDF_EXPORT, $id);
        return (new BinaryFileResponse($complaintPdfExporter->execute((int)$id, $request->query->get('locale', 'ru')), 200, [], true))->deleteFileAfterSend();
    }

    /**
     * @Route(path="/{id}/doc", methods={"GET"}, requirements={"id" = "\d+"})
     * @IsGranted("ROLE_USER")
     * @param $id
     * @param Connection $connection
     * @param ComplaintDocExporter $complaintDocExporter
     * @param Request $request
     * @return BinaryFileResponse
     * @Get(
     *     path="/api/complaints/{id}/doc",
     *     tags={"Жалобы"},
     *     summary="Экспорт в doc",
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="id", in="path", description="Id жалобы", @Schema(type="integer")),
     *     @Parameter(name="locale", in="query", description="Язык", @Schema(type="string", enum={"ru", "kz"})),
     *     @Response(response=200, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=403, description=""),
     *     @Response(response=404, description=""),
     * )
     */
    public function docExport($id, Connection $connection, ComplaintDocExporter $complaintDocExporter, Request $request)
    {
        $this->denyAccessUnlessGranted(ComplaintPermissions::PDF_EXPORT, $id);
        return (new BinaryFileResponse($complaintDocExporter->execute((int)$id, $request->query->get('locale', 'ru')), 200, [], true))->deleteFileAfterSend();
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/initialData", methods={"GET"})
     * @Get(
     *     path="/api/complaints/initialData",
     *     security={{"clientAuth": {}}},
     *     summary="Получение предустановленных значений",
     *     tags={"Жалобы"},
     *     @Parameter(name="objectId", in="query", description="Id объекта", @Schema(type="integer", nullable=true)),
     *     @Response(response=200, description="", @JsonContent(ref="#/components/schemas/Complaint"))
     * )
     * @param Connection $connection
     * @param Request $request
     * @param FindCityIdByLocation $findCityIdByLocation
     * @return ComplaintData
     */
    public function initialData(Connection $connection, Request $request, FindCityIdByLocation $findCityIdByLocation)
    {
        $authority = $connection->createQueryBuilder()
            ->select('id')
            ->from('complaint_authorities')
            ->where('deleted_at IS NULL')
            ->orderBy('id', 'desc')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();


        $lastComplaint = $connection->createQueryBuilder()
            ->select('complainant', 'remember_personal_data')
            ->from('complaints')
            ->andWhere('complainant_id = :userId')
            ->setParameter('userId', $this->getUser()->id())
            ->orderBy('id', 'desc')
            ->setMaxResults(1)
            ->execute()
            ->fetch();


        $cityId = Cities::list()[0]['id'];
        $object = $connection->createQueryBuilder()
            ->select([
                'objects.title',
                'objects.address',
                'ST_X(ST_AsText(objects.point_value)) as lon',
                'ST_Y(ST_AsText(objects.point_value)) as lat',
            ])
            ->from('objects')
            ->andWhere('objects.id = :id')
            ->setParameter('id', $request->query->getInt('objectId'))
            ->execute()
            ->fetch();

        if ($object) {
            $cityId = $findCityIdByLocation->execute($object['lat'], $object['lon']);
        }

        $result = [
            'content' => [
                'objectName' => $object['title'] ?? '',
                'cityId' => null,
                'street' => $object['address'] ?? ''
            ]
        ];

        $complainant = new Complainant();
        if ($lastComplaint && $lastComplaint['remember_personal_data']) {
            $complainant = $connection->convertToPHPValue($lastComplaint['complainant'], Complainant::class);
        }


        return new ComplaintData(
            $complainant,
            new ComplaintType1(
                'complaint1',
                new \DateTimeImmutable(),
                $object['title'] ?? '',
                $cityId,
                $object['address'] ?? '',
                '',
                '',
                '',
                [''],
                []
            ),
            $authority,
            $lastComplaint && $lastComplaint['remember_personal_data'],
            $request->query->getInt('objectId', null)
        );
    }

    /**
     * @Route(path="/type2Attributes", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @Get(
     *     path="/api/complaints/type2Attributes",
     *     summary="Аттрибуты для жалобы на на отсутствие доступа на объект или несоответствии функциональных зон объекта требованиям нормативного законодательства",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=200, description=""),
     * )
     */
    public function complaintAttributes()
    {
        return ComplaintAttributes::$attributes;
    }

    /**
     * @Route(path="/statistic", methods={"GET"})
     * @Get(
     *     path="/api/complaints/statistic",
     *     summary="Статистика по жалобам",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="city_id", in="query", description="Id города", @Schema(type="integer", nullable=true)),
     *     @Parameter(name="year_id", in="query", description="Год", @Schema(type="integer", nullable=true)),
     *     @Parameter(name="dateFrom", in="query", description="Период с", @Schema(type="string", nullable=true)),
     *     @Parameter(name="dateTo", in="query", description="Период по", @Schema(type="string", nullable=true)),
     *     @Response(response=200, description=""),
     * )
     * @param Connection $connection
     * @param Request $request
     */
    public function statistic(Connection $connection, Request $request)
    {
        $query = $connection->createQueryBuilder()
            ->select('COUNT(complaints.id) as count')
            ->addSelect('EXTRACT(YEAR FROM complaints.created_at) as year')
            ->addSelect('EXTRACT(MONTH FROM complaints.created_at) as month')
            ->addSelect('cities.name as city_name')
            ->addSelect('cities.id as city_id')
            ->from('complaints', 'complaints')
            ->leftJoin('complaints', 'cities', 'cities', '(complaints.content->>\'cityId\')::INT = cities.id');

        if ($request->query->getInt('city_id') != 0) {
            $query = $query
                ->andWhere('cities.id = :cityId')
                ->setParameter('cityId', $request->query->getInt('city_id'));
        }

        if ($request->query->getInt('year_id') != 0) {
            $query = $query
                ->andWhere('EXTRACT(YEAR FROM complaints.created_at) = :yearId')
                ->setParameter('yearId', $request->query->getInt('year_id'));
        }

        if ($request->query->getAlnum('dateFrom') != '') {
            $dateFrom = date_create($request->query->getAlnum('dateFrom'));
            if ($request->query->getAlnum('dateTo') != '') {
                $dateTo = date_create($request->query->getAlnum('dateTo'));
                $query = $query
                    ->andWhere('complaints.created_at > :dateFrom')
                    ->andWhere('complaints.created_at < :dateTo')
                    ->setParameter('dateFrom', date_format($dateFrom, 'Y-m-d H:i:s'))
                    ->setParameter('dateTo', date_format($dateTo, 'Y-m-d H:i:s'));
            } else {
                $query = $query
                    ->andWhere('complaints.created_at > :dateFrom')
                    ->setParameter('dateFrom', date_format($dateFrom, 'Y-m-d H:i:s'));
            }

        }

        $query = $query
            ->groupBy('cities.id, year, month')
            ->execute()
            ->fetchAll();

        if (count($query) == 0)
            return [];

        $years = array();

        foreach ($query as $q) {
            if (!in_array($q['year'], $years))
                array_push($years, $q['year']);
        }

        return ['years' => $years, 'result' => $query];

    }

    /**
     * @Route(path="/export/excel", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/complaints/export/excel",
     *     summary="Экспорт статистики по жалобам и обращениям",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="city_id", in="query", description="Id города", @Schema(type="integer", nullable=true)),
     *     @Parameter(name="dateFrom", in="query", description="Период с", @Schema(type="string", nullable=true)),
     *     @Parameter(name="dateTo", in="query", description="Период по", @Schema(type="string", nullable=true)),
     *     @Response(response=200, description=""),
     * )
     */
    public function exportToExcel(Connection $connection, Request $request)
    {
        $data = $this->statisticAll($connection, $request);
        $export = new ExportToExcelService($request);
        $export->fillData($data);

        try {
            $filePath = $export->writeFile();
            $file = new File($filePath);
            return $this->file($file, 'Статистика_по_жалобам_и_обращениям.xlsx')->deleteFileAfterSend();
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }

    /**
     * @Route(path="/allstatistic", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/complaints/allstatistic",
     *     summary="Общая статистика по жалобам и обращениям",
     *     tags={"Жалобы"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="city_id", in="query", description="Id города", @Schema(type="integer", nullable=true)),
     *     @Parameter(name="dateFrom", in="query", description="Период с", @Schema(type="string", nullable=true)),
     *     @Parameter(name="dateTo", in="query", description="Период по", @Schema(type="string", nullable=true)),
     *     @Response(response=200, description=""),
     * )
     * @param Connection $connection
     * @param Request $request
     */
    public function statisticAll(Connection $connection, Request $request)
    {
        $queryComplaints = $connection->createQueryBuilder()
            ->select('c.name as city_name')
            ->addSelect('c.id as city_id')
            ->from('cities', 'c')
            ->leftJoin('c', 'complaints', 'cm', '(cm.content->>\'cityId\')::INT = c.id')
            ->leftJoin('c', 'feedback', 'f', 'c.id = f.city_id');

        if ($request->query->getInt('city_id') != 0) {
            $queryComplaints = $queryComplaints
                ->andWhere('c.id = :cityId')
                ->setParameter('cityId', $request->query->getInt('city_id'));
        }

        if ($request->query->getAlnum('dateFrom') != '') {
            $dateFrom = date_create($request->query->getAlnum('dateFrom'));
            if ($request->query->getAlnum('dateTo') != '') {
                $dateTo = date_create($request->query->getAlnum('dateTo'));
                $dateTo->add(new \DateInterval('P1D'));
                $queryComplaints = $queryComplaints
                    ->addSelect('COUNT(DISTINCT (CASE WHEN cm.created_at > :dateFrom and cm.created_at < :dateTo THEN cm.id END)) as complaint_count')
                    ->addSelect('COUNT(DISTINCT (CASE WHEN f.created_at > :dateFrom and f.created_at < :dateTo THEN f.id END)) as feedback_count')
                    ->setParameter('dateFrom', date_format($dateFrom, 'Y-m-d H:i:s'))
                    ->setParameter('dateTo', date_format($dateTo, 'Y-m-d H:i:s'));
            }
        }
        else {
            $queryComplaints = $queryComplaints
            ->addSelect('COUNT(DISTINCT cm.id) as complaint_count')
            ->addSelect('COUNT(DISTINCT f.id) as feedback_count');
        }

        $data = $queryComplaints
            ->orderBy('c.name')
            ->groupBy('c.id')
            ->execute()
            ->fetchAll();

        if (!$request->query->get('city_id') &&
            (!$request->query->get('dateFrom') && !$request->query->get('dateTo'))) {

            $arr = [
                'city_name' => 'Весь РК',
                'complaint_count' => 0,
                'feedback_count' => 0,
            ];
            foreach ($data as $datum) {
                $arr['complaint_count'] += $datum['complaint_count'];
                $arr['feedback_count'] += $datum['feedback_count'];
            }
            array_unshift($data, $arr);
        }

        return $data;
    }
}
