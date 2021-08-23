<?php
declare(strict_types=1);

namespace App\Complaints;

use App\Cities\Cities;
use App\Cities\FindCityIdByLocation;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\OfficeRequest;
use TheCodingMachine\Gotenberg\RequestException;

/**
 * @IsGranted("ROLE_USER")
 * @Route(path="/api/complaints")
 */
final class ComplaintController extends AbstractController
{
    private $projectDir;

    /**
     * ComplaintController constructor.
     * @param $projectDir
     */
    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }


    /**
     * @Route(path="/validate", methods={"POST"})
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
            $complaintData->objectId,
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
        return (new BinaryFileResponse($complaintPdfExporter->execute((int) $id, $request->query->get('locale', 'ru')), 200, [], true))->deleteFileAfterSend();
    }

    /**
     * @Route(path="/{id}/doc", methods={"GET"}, requirements={"id" = "\d+"})
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
        return (new BinaryFileResponse($complaintDocExporter->execute((int) $id, $request->query->get('locale', 'ru')), 200, [], true))->deleteFileAfterSend();
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
}
