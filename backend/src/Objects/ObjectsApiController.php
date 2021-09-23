<?php
declare(strict_types=1);

namespace App\Objects;

use App\Infrastructure\FileReference;
use App\Infrastructure\TranslatableListener;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\EventsHistory\EventData;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Imgproxy\UrlBuilder;
use OpenApi\Annotations\ExternalDocumentation;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Safe\Exceptions\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Contracts\Translation\TranslatorInterface;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\RequestException;
use TheCodingMachine\Gotenberg\URLRequest;
use Webmozart\Assert\Assert;

/**
 * @Route(path="/api/objects")
 */
final class ObjectsApiController extends AbstractController
{
    /**
     * @var CsvFileLoader
     */
    private CsvFileLoader $csvFileLoader;

    public function __construct(CsvFileLoader $csvFileLoader, ParameterBagInterface $params)
    {
        $this->csvFileLoader = $csvFileLoader;
        $this->params = $params;
    }

    /**
     * @Route(path="/ymaps", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return JsonResponse
     */
    public function index(Request $request, Connection $connection)
    {
        $boundary = explode(',', $request->query->get('bbox'));

        $zoom = $request->query->get('zoom');

        $accessibilityLevels = $request->query->get('accessibilityLevels', []);
        $disabilitiesCategory = $request->query->get('disabilitiesCategory', AccessibilityScore::SCORE_CATEGORIES[0]);
        Assert::oneOf($disabilitiesCategory, AccessibilityScore::SCORE_CATEGORIES);

        $clusteringLevels = [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 2,
            5 => 2,
            6 => 3,
            7 => 3,
            8 => 3,
            9 => 3,
            10 => 4,
            11 => 5,
            12 => 5,
            13 => 5,
            14 => 6,
            15 => 6,
            16 => 7,
            17 => 8
        ];

        $precision = $clusteringLevels[$zoom] ?? 11;

        $q1 = $connection->createQueryBuilder()
            ->select([
                'COUNT(*) AS number',
                'ST_GEOHASH(point_value, :precision) AS hash',
                "SUM(case when overall_score_$disabilitiesCategory = 'not_accessible' THEN 1 else 0 end) as not_accessible",
                "SUM(case when overall_score_$disabilitiesCategory = 'partial_accessible' THEN 1 else 0 end) as partial_accessible",
                "SUM(case when overall_score_$disabilitiesCategory = 'full_accessible' THEN 1 else 0 end) as full_accessible",
                'ST_XMIN(ST_COLLECT(objects.point_value::GEOMETRY)) AS p1x',
                'ST_YMIN(ST_COLLECT(objects.point_value::GEOMETRY)) AS p1y',
                'ST_XMAX(ST_COLLECT(objects.point_value::GEOMETRY)) AS p2x',
                'ST_YMAX(ST_COLLECT(objects.point_value::GEOMETRY)) AS p2y',
                'ST_X(ST_CENTROID(ST_COLLECT(objects.point_value::GEOMETRY))) AS long',
                'ST_Y(ST_CENTROID(ST_COLLECT(objects.point_value::GEOMETRY))) AS lat'
            ])
            ->from('objects')
            ->andWhere('ST_Contains(ST_MAKEENVELOPE(:x1,:y1,:x2,:y2, 4326)::geometry, point_value::geometry)')
            ->andWhere('objects.deleted_at IS NULL')
            ->groupBy('hash' )
            ->having('COUNT(*) > 1')
            ->setParameters([
                'x1' => $boundary[1],
                'y1' => $boundary[0],
                'x2' => $boundary[3],
                'y2' => $boundary[2],
                'precision' => $precision
            ]);

        if (count($accessibilityLevels)) {
            $q1->andWhere("overall_score_$disabilitiesCategory IN (:levels)")
                ->setParameter('levels', $accessibilityLevels, Connection::PARAM_STR_ARRAY);
        }

        $categories = $request->query->get('categories', []);
        if (count($categories)) {
            $q1->andWhere('category_id IN (:categories)')
                ->setParameter('categories', $categories, Connection::PARAM_STR_ARRAY);
        }

        if ($request->query->get('search', false)) {
            $q1->andWhere('TO_TSVECTOR(title) @@ WEBSEARCH_TO_TSQUERY(:search)')
                ->setParameter('search', $request->query->get('search'));
        }

        $clusters = [];

        if ($zoom < 19) {
            $clusters = $q1->execute()->fetchAll();
        }

        $ids = array_column($clusters, 'hash');

        $q2 = $connection->createQueryBuilder()
            ->select([
                'objects.id',
                'categories.icon',
                "overall_score_$disabilitiesCategory as score",
                'ST_X(point_value::geometry) as long',
                'ST_Y(point_value::geometry) as lat',
            ])
            ->from('objects')
            ->leftJoin('objects', 'object_categories', 'categories', 'categories.id = objects.category_id')
            ->andWhere('ST_Contains(ST_MAKEENVELOPE(:x1,:y1,:x2,:y2, 4326)::geometry, point_value::geometry)')
            ->andWhere('objects.deleted_at IS NULL')
            ->andWhere('ST_GEOHASH(point_value, :precision) NOT IN (:ids)')
            ->setParameters([
                'x1' => $boundary[1],
                'y1' => $boundary[0],
                'x2' => $boundary[3],
                'y2' => $boundary[2],
                'ids' => array_merge($ids, ['']),
                'precision' => $precision
            ], [
                'ids' => Connection::PARAM_STR_ARRAY
            ]);

        if (count($accessibilityLevels)) {
            $q2->andWhere("overall_score_$disabilitiesCategory IN (:levels)")
                ->setParameter('levels', $accessibilityLevels, Connection::PARAM_STR_ARRAY);
        }

        if (count($categories)) {
            $q2->andWhere('category_id IN (:categories)')
                ->setParameter('categories', $categories, Connection::PARAM_STR_ARRAY);
        }
        if ($request->query->get('search', false)) {
            $q2->andWhere('TO_TSVECTOR(title) @@ websearch_to_tsquery(:search)')
                ->setParameter('search', $request->query->get('search'));
        }
        $points = $q2->execute()->fetchAll();
        $pointsPrepared = array_map(function ($item) {
            $colors = [
                AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE => '#F8AC1A',
                AccessibilityScore::SCORE_NOT_ACCESSIBLE => '#DE1220',
                AccessibilityScore::SCORE_NOT_PROVIDED => '#7B95A7',
                AccessibilityScore::SCORE_FULL_ACCESSIBLE => '#3DBA3B'
            ];

            return [
                'type' => 'Feature',
                'id' => $item['id'],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$item['lat'], $item['long']]
                ],
                'options' => [
                    'iconLayout' => 'custom#objectIconLayout',
                    'iconShape' => [
                        'type' => 'Rectangle',
                        'coordinates' => [
                            [-25, -60], [25, 0]
                        ]
                    ]
                ],
                'properties' => [
                    'color' => $colors[$item['score']],
                    'icon' => $item['icon'],
                    'background' => $colors[$item['score']],
                    'fill' => 'white',
                ]
            ];
        }, $points);


        $clustersPrepared = array_map(function ($item) {
            return [
                'type' => 'Cluster',
                'levels' => [
                    'not_accessible' => $item['not_accessible'],
                    'partial' => $item['partial_accessible'],
                    'full' => $item['full_accessible']
                ],
                'id' => $item['hash'],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$item['lat'], $item['long']]
                ],
                'bbox' => [[$item['p1y'], $item['p1x']], [$item['p2y'], $item['p2x']]],
                'number' => $item['number'],
                'features' => []
            ];
        }, $clusters);


        $clusters = ['type' => 'FeatureCollection',
            'features' => array_merge($clustersPrepared, $pointsPrepared)];

        $response = new JsonResponse($clusters);
        $response->setCallback($request->query->get('callback'));
        return $response;
    }

    /**
     * @Route(path="/{id}", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @param UrlBuilder $urlBuilder
     * @param Request $request
     * @return array|NotFoundHttpException
     * @Get(
     *     path="/api/objects/{id}",
     *     summary="Информация о объекте",
     *     tags={"Объекты"},
     *     @Parameter(name="id", in="path", required=true, description="id объекта", @Schema(type="integer"), example=172709),
     *     @Parameter(name="disabilitiesCategory", in="query", required=false, description="Категория пользователя", @Schema(type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_CATEGORIES)),
     *     @Response(response=404, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             properties={
     *                 @Property(property="title", type="string"),
     *                 @Property(property="address", type="string"),
     *                 @Property(property="description", type="string"),
     *                 @Property(property="category", type="string"),
     *                 @Property(property="subCategory", type="string"),
     *                 @Property(property="coordinates", type="array", @Items(type="number", format="float"), example={52.2957528444857, 76.9407562711638}),
     *                 @Property(property="overallScore", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                 @Property(property="color", type="string", example="#F8AC1A"),
     *                 @Property(
     *                     property="scoreByZones",
     *                     type="object",
     *                     @Property(property="parking", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="entrance", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="movement", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="service", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="toilet", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="navigation", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="serviceAccessibility", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                     @Property(property="kidsAccessibility", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS)
     *                 ),
     *                 @Property(property="icon", type="string", example="fa-credit-card"),
     *                 @Property(
     *                     property="photos",
     *                     type="array",
     *                     @Items(
     *                         @Property(property="previewUrl", type="string"),
     *                         @Property(property="viewUrl", type="string"),
     *                         @Property(property="date", type="string", format="date-time"),
     *                     )
     *                 ),
     *                 @Property(property="videos", type="array", @Items(type="string")),
     *                 @Property(
     *                     property="reviews",
     *                     type="array",
     *                     @Items(
     *                         type="object",
     *                         @Property(type="string", property="author"),
     *                         @Property(type="string", property="text"),
     *                         @Property(type="string", property="date", format="date-time"),
     *                     ),
     *                 ),
     *                 @Property(
     *                     property="history",
     *                     type="array",
     *                     @Items(
     *                         type="object",
     *                         @Property(type="string", property="name"),
     *                         @Property(type="string", property="date", format="date-time"),
     *                         @Property(
     *                             type="object",
     *                             property="data",
     *                             @Property(property="type", type="string", enum={"review_created", "verification_rejected", "verification_confirmed", "supplemented"})
     *                         ),
     *                     ),
     *                 ),
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     @Property(property="form", type="string", enum={"small", "middle", "full"}),
     *                     @Property(
     *                         property="zones",
     *                         type="object",
     *                         @Property(property="parking", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="entrance1", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="entrance2", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="entrance3", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="movement", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="service", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="toilet", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="navigation", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="serviceAccessibility", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                         @Property(property="kidsAccessibility", type="object", properties={"default": @Property(property="attribute1", type="string", enum=App\Objects\Adding\Attribute::ATTRIBUTES)}),
     *                     )
     *                 ),
     *                 @Property(property="verificationStatus", type="string", enum=App\Objects\Verification\Verification::STATUSES)
     *             }
     *         )
     *     )
     * )
     */
    public function object($id, Connection $connection, UrlBuilder $urlBuilder, Request $request, EntityManagerInterface $entityManager)
    {
        $disabilitiesCategory = $request->query->get('disabilitiesCategory', AccessibilityScore::SCORE_CATEGORIES[0]);
        Assert::oneOf($disabilitiesCategory, AccessibilityScore::SCORE_CATEGORIES);

        $object = $connection->createQueryBuilder()
            ->select([
                'objects.title',
                'objects.address',
                'objects.description',
                'objects.overall_score_movement',
                'objects.overall_score_limb',
                'objects.overall_score_vision',
                'objects.overall_score_hearing',
                'objects.overall_score_intellectual',
                'objects.overall_score_kids',
                'objects.zones',
                'objects.category_id as sub_category_id',
                'object_categories.id as category_id',
                'ST_X(ST_AsText(objects.point_value)) as long',
                'ST_Y(ST_AsText(objects.point_value)) as lat',
                'sub_categories.title as sub_category',
                'sub_categories.icon as sub_category_icon',
                'object_categories.icon as category_icon',
                'object_verifications.status as verification_status',
                'objects.zones->>\'type\' as form_type',
                'objects.videos'
            ])
            ->from('objects')
            ->leftJoin('objects', 'object_categories', 'sub_categories', 'sub_categories.id = objects.category_id')
            ->leftJoin('sub_categories', 'object_categories', 'object_categories', 'sub_categories.parent_id = object_categories.id')
            ->leftJoin('objects', 'object_verifications', 'object_verifications', 'object_verifications.id = objects.uuid')
            ->andWhere('objects.id = :id')
            ->andWhere('objects.deleted_at IS NULL')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$object) {
            throw new NotFoundHttpException();
        }

        $categoryQuery = $entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $object['category_id'])
            ->getQuery();

        $categoryQuery->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $categoryQuery->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $request->getLocale()
        );

        $categoryQuery->setHint(
            TranslatableListener::HINT_FALLBACK,
            1
        );

        $subCategoryQuery = $entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $object['sub_category_id'])
            ->getQuery();

        $subCategoryQuery->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $subCategoryQuery->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $request->getLocale()
        );

        $subCategoryQuery->setHint(
            TranslatableListener::HINT_FALLBACK,
            1
        );

        /**
         * @var $category Category
         */
        $category = $categoryQuery->getResult()[0];


        /**
         * @var $subCategory Category
         */
        $subCategory = $subCategoryQuery->getResult()[0];

        /**
         * @var Zones
         */
        $zones = $connection->convertToPHPValue($object['zones'], Zones::class);

        $photos = $connection->createQueryBuilder()
            ->select('*')->from('objects_photos_history')
            ->andWhere('object_id = :objectId')
            ->setParameter('objectId', $id)
            ->orderBy('date', 'desc')
            ->execute()
            ->fetchAll();

        $scoresByZone = [
            'parking' => $zones->parking->accessibilityScore(),
            'entrance' => AccessibilityScore::average(...[
                $zones->entrance1->accessibilityScore(),
                $zones->entrance2 ? $zones->entrance2->accessibilityScore() : null,
                $zones->entrance3 ? $zones->entrance3->accessibilityScore() : null,
            ]),
            'movement' => $zones->movement->accessibilityScore(),
            'service' => $zones->service->accessibilityScore(),
            'toilet' => $zones->toilet->accessibilityScore(),
            'navigation' => $zones->navigation->accessibilityScore(),
            'serviceAccessibility' => $zones->serviceAccessibility->accessibilityScore(),
            'kidsAccessibility' => $zones->kidsAccessibility->accessibilityScore()
        ];

        //  $baseUrl = $request->getSchemeAndHttpHost();
        $baseUrl = '';
        $reviews = $connection->createQueryBuilder()
            ->select([
                'object_reviews.created_at as "createdAt"',
                'object_reviews.text',
                'authors.full_name->>\'firstAndLast\' as author'
            ])
            ->from('object_reviews')
            ->leftJoin('object_reviews', 'users', 'authors', 'authors.id = object_reviews.author_id')
            ->andWhere('object_reviews.deleted_at IS NULL')
            ->andWhere('object_reviews.is_published = true')
            ->orderBy('object_reviews.created_at', 'desc')
            ->andWhere('object_reviews.object_id = :objectId')
            ->setParameter('objectId', $id)
            ->execute()
            ->fetchAll();

        $events = $connection->createQueryBuilder()->select([
            'data',
            'date',
            'full_name->>\'firstAndLast\' as name'
        ])
            ->from('objects_events_history')
            ->leftJoin('objects_events_history', 'users', 'users', 'users.id = objects_events_history.user_id')
            ->andWhere('objects_events_history.object_id = :objectId')
            ->setParameter('objectId', $id)
            ->orderBy('date', 'desc')
            ->execute()
            ->fetchAll();

        $videos = $connection->convertToPHPValue($object['videos'], 'json');
        $videos = array_filter($videos, function ($video) {
            return !empty($video);
        });
        $videos = array_map(function ($video) {
            $result = [];
            $parsed = parse_url($video);

            try {
                parse_str($parsed['query'] ?? '', $result);

                return [
                    'url' => $video,
                    'thumbnail' => sprintf('https://i.ytimg.com/vi/%s/maxresdefault.jpg', $result['v']),
                    'videoId' => $result['v']
                ];
            } catch (\Exception $exception) {
            }
        }, $videos);

        return [
            'title' => $object['title'],
            'address' => $object['address'],
            'description' => strip_tags($object['description']),
            'subCategory' => $subCategory->getTitle(),
            'categoryId' => $object['category_id'],
            'subCategoryId' => $object['sub_category_id'],
            'category' => $category->getTitle(),
            'coordinates' => [
                (float)$object['lat'], (float)$object['long']
            ],
            'overallScore' => $object["overall_score_$disabilitiesCategory"],
            'color' => [
                AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE => '#F8AC1A',
                AccessibilityScore::SCORE_NOT_ACCESSIBLE => '#DE1220',
                AccessibilityScore::SCORE_NOT_PROVIDED => '#7B95A7',
                AccessibilityScore::SCORE_UNKNOWN => '#7B95A7',
                AccessibilityScore::SCORE_FULL_ACCESSIBLE => '#3DBA3B'
            ][$object["overall_score_$disabilitiesCategory"]],
            'scoreByZones' => array_map(function (AccessibilityScore $accessibilityScore) use ($disabilitiesCategory) {
                return $accessibilityScore->{$disabilitiesCategory};
            }, $scoresByZone),
            'icon' => $object['sub_category_icon'] ? $object['sub_category_icon'] : $object['category_icon'],
            'photos' => array_map(function ($photo) use ($connection, $urlBuilder, $baseUrl) {
                /**
                 * @var FileReference $file
                 */
                $file = $connection->convertToPHPValue($photo['file'], FileReference::class);
                return [
                    'date' => $connection->convertToPHPValue($photo['date'], 'datetimetz_immutable'),
                    'previewUrl' => $baseUrl . $urlBuilder->build('local:///storage/' . $file->relativePath, 600, 400)->toString(),
                    'viewUrl' => $baseUrl . $urlBuilder->build('local:///storage/' . $file->relativePath, 2560, 1440)->toString()
                ];
            }, $photos),
            'videos' => array_reverse(array_filter($videos, function ($video) {
                return !empty($video);
            })),
            'reviews' => array_map(function ($review) use ($connection) {
                return array_replace($review, [
                    'createdAt' => $connection->convertToPHPValue($review['createdAt'], 'datetimetz_immutable')
                ]);
            }, $reviews),
            'history' => array_map(function ($event) use ($connection) {
                return [
                    'name' => $event['name'],
                    'date' => $connection->convertToPHPValue($event['date'], 'datetimetz_immutable'),
                    'data' => $connection->convertToPHPValue($event['data'], EventData::class)
                ];
            }, $events),
            'attributes' => [
                'form' => $object['form_type'],
                'zones' => array_map(function (?Zone $zone) {
                    return $zone->attributes;
                }, array_filter((array)$zones, function ($zone) {
                    return !is_null($zone);
                }))
            ],
            'verificationStatus' => $object['verification_status']
        ];
    }

    /**
     * @Route(path="/attributes", methods={"GET"})
     * @param TranslatorInterface $translator
     * @return array
     */
    public function attributes(TranslatorInterface $translator)
    {
        $this->csvFileLoader->setCsvControl(',');
        array_walk_recursive(AttributesConfiguration::$configuration, function (&$val) use ($translator) {
            if (is_string($val)) {
                $val = $translator->trans($val, [], 'attributes');
            }
        });

        return AttributesConfiguration::$configuration;
    }

    /**
     * @Route(path="/{id}/pdf", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Request $request
     * @param MapObject $mapObject
     * @param Client $client
     * @return BinaryFileResponse
     * @throws ClientException
     * @throws FilesystemException
     * @throws RequestException
     * @Get(
     *     path="/api/objects/{id}/pdf",
     *     summary="Экспорт данных в pdf",
     *     tags={"Объекты"},
     *     @Parameter(name="id", in="path", required=true, description="id объекта", @Schema(type="integer"), example=172709),
     *     @Response(response=200, description="PDF файл")
     * )
     */
    public function pdf(Request $request, MapObject $mapObject, Client $client)
    {
        $request = new URLRequest($this->params->get('app.frontend_url').'/objects/pdf?id=' . $mapObject->id());
        $request->setMargins([0, 0, 0, 0]);
        $request->setPaperSize(URLRequest::A4);
        $path = tempnam('/tmp', 'pdf');
        $client->store($request, $path);
        return (new BinaryFileResponse($path, 200, [], true))->deleteFileAfterSend();
    }

    /**
     * @Route(path="/search", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array|mixed[]
     * @Get(
     *     path="/api/objects/search",
     *     summary="Поиск объекта",
     *     tags={"Объекты"},
     *     @Parameter(name="cityId", in="query", required=true, description="id города", @Schema(type="integer"), example=9103),
     *     @Parameter(name="query", in="query", required=true, description="Текст поиска", @Schema(type="string")),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *              type="array",
     *              @Items(
     *                  @Property(type="integer", property="id", description="Id объекта"),
     *                  @Property(type="string", property="title", description="Наименование объекта"),
     *                  @Property(type="string", property="address", description="Адрес объекта"),
     *                  @Property(type="string", property="category", description="Категория объекта"),
     *                  @Property(type="string", property="icon", description="Иконка объекта"),
     *              )
     *         )
     *     )
     * )
     */
    public function search(Request $request, Connection $connection)
    {
        if (empty($request->query->get('query'))) {
            return [];
        }
        $cityId = $request->query->get('cityId');
        $cityGeometry = 'SELECT geometry FROM cities_geometry WHERE ST_CONTAINS(geometry, (SELECT ST_CENTROID(cities.bbox) FROM cities WHERE id = :id))';
        return $connection->createQueryBuilder()
            ->select([
                'objects.id',
                'objects.title',
                'objects.address',
                'object_categories.title as category',
                'object_categories.icon',
            ])
            ->from('objects')
            ->join('objects', 'object_categories', 'object_categories', 'object_categories.id = objects.category_id')
            ->andWhere("ST_CONTAINS(($cityGeometry), objects.point_value::geometry)")
            ->andWhere('SIMILARITY(CONCAT(objects.title, \' \', objects.address, \' \', object_categories.title, \' \', objects.other_names), :search) > 0')
            ->andWhere('deleted_at IS NULL')
            ->setParameter('search', $request->query->get('query', ''))
            ->setParameter('id', $cityId)
            ->setMaxResults(10)
            ->orderBy('SIMILARITY(concat(objects.title, \' \', objects.address, \' \', object_categories.title), :search)', 'desc')
            ->execute()
            ->fetchAll();
    }

    /**
     * @Route(path="/filter", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array|mixed[]
     * @Get(
     *     path="/api/objects/filter",
     *     summary="Поиск объектов",
     *     tags={"Объекты"},
     *     @Parameter(name="accessibilityLevels", in="query", description="Оценки доступности", style="deepObject", @Schema(type="array", @Items(type="string", enum={"full_accessiblie", "partial_accessible", "not_accessible"}))),
     *     @Parameter(name="disabilitiesCategory", in="query", required=false, description="Категория пользователя", @Schema(type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_CATEGORIES)),
     *     @Parameter(name="subCategoryId", in="query", description="id подкатегории", @Schema(type="integer")),
     *     @Parameter(name="cityId", in="query", required=true, description="id города", @Schema(type="integer"), example=9103),
     *     @Parameter(name="query", in="query", required=true, description="Текст поиска", @Schema(type="string")),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *              type="array",
     *              @Items(
     *                  @Property(type="integer", property="id", description="Id объекта"),
     *                  @Property(type="string", property="title", description="Наименование объекта"),
     *                  @Property(type="string", property="address", description="Адрес объекта"),
     *                  @Property(type="string", property="category", description="Категория объекта"),
     *                  @Property(type="string", property="score", description="Оценка доступности объекта", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *              )
     *         )
     *     )
     * )
     */
    public function filter(Request $request, Connection $connection)
    {
        $accessibilityLevels = $request->query->get('accessibilityLevels', []);
        $disabilitiesCategory = $request->query->get('disabilitiesCategory', AccessibilityScore::SCORE_CATEGORIES[0]);
        Assert::oneOf($disabilitiesCategory, AccessibilityScore::SCORE_CATEGORIES);
        $subcategoryId = $request->query->get('subCategoryId');

        $cityId = $request->query->get('cityId');
        $cityGeometry = 'SELECT geometry FROM cities_geometry WHERE ST_CONTAINS(geometry, (SELECT ST_CENTROID(cities.bbox) FROM cities WHERE id = :id))';
        $qb = $connection->createQueryBuilder()
            ->select([
                'objects.id',
                'objects.title',
                'objects.address',
                'object_categories.title as category',
                "overall_score_$disabilitiesCategory as score",
            ])
            ->from('objects')
            ->join('objects', 'object_categories', 'object_categories', 'object_categories.id = objects.category_id')
            ->andWhere("ST_CONTAINS(($cityGeometry), objects.point_value::geometry)")
            ->setParameter('id', $cityId)
            ->setMaxResults(10)
            ->andWhere('deleted_at IS NULL');
        if (count($accessibilityLevels)) {
            $qb->andWhere("overall_score_$disabilitiesCategory IN (:levels)")
                ->setParameter('levels', $accessibilityLevels, Connection::PARAM_STR_ARRAY);
        }

        if (!empty($subcategoryId)) {
            $qb->andWhere('objects.category_id = :subCategoryId')
                ->setParameter('subCategoryId', $subcategoryId);
        }

        if (!empty($request->query->get('query'))) {
            $qb->andWhere('SIMILARITY(CONCAT(objects.title, \' \', objects.address, \' \', object_categories.title), :search) > 0')
                ->orderBy('SIMILARITY(concat(objects.title, \' \', objects.address, \' \', object_categories.title), :search)', 'desc')
                ->setParameter('search', $request->query->get('query', ''));
        }

        return $qb
            ->execute()
            ->fetchAll();
    }

    /**
     * @Route(path="/map", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return JsonResponse
     * @Get(
     *     path="/api/objects/map",
     *     summary="Объекты для отображения на карте",
     *     tags={"Объекты"},
     *     @ExternalDocumentation(url="http://bboxfinder.com/#52.252300,76.838400,52.333200,77.102100"),
     *     @Parameter(name="zoom", in="query", required=true, description="Масштаб", @Schema(type="integer"), example=14),
     *     @Parameter(name="bbox", in="query", required=true, description="Массив географических координат углов видимой области карты", @Schema(type="string"), example="52.2523,76.8384,52.3332,77.1021"),
     *     @Parameter(name="categories", in="query", description="Id подкатегорий", style="deepObject", @Schema(type="array", @Items(type="integer"))),
     *     @Parameter(name="accessibilityLevels", in="query", description="Оценки доступности", style="deepObject", @Schema(type="array", @Items(type="string", enum={"full_accessiblie", "partial_accessible", "not_accessible"}))),
     *     @Parameter(name="disabilitiesCategory", in="query", required=false, description="Категория пользователя", @Schema(type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_CATEGORIES)),
     *     @Response(
     *         response="200",
     *         description="",
     *         @JsonContent(
     *             @Property(
     *                 property="clusters",
     *                 description="Список кластеров",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="string"),
     *                     @Property(
     *                         property="coordinates",
     *                         type="array",
     *                         example={52.253724266066, 76.9443852187141},
     *                         @Items(
     *                             type="number",
     *                             format="float",
     *                         )
     *                     ),
     *                     @Property(
     *                         property="bbox",
     *                         type="array",
     *                         example={{51.0006766, 71.2244414}, {51.3511101, 71.7851913}},
     *                         @Items(
     *                             type="array",
     *                             @Items(
     *                                type="array",
     *                                @Items(type="number", format="float")
     *                             )
     *                         )
     *                     ),
     *                     @Property(property="itemsCount", type="number", example=3, description="Количество меток в кластере")
     *                 ),
     *             ),
     *             @Property(
     *                 property="points",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="number", description="Id объекта"),
     *                     @Property(
     *                         property="coordinates",
     *                         type="array",
     *                         example={52.253724266066, 76.9443852187141},
     *                         @Items(
     *                             type="number",
     *                             format="float",
     *                         )
     *                     ),
     *                     @Property(property="color", type="string"),
     *                     @Property(property="icon", type="string"),
     *                     @Property(property="overallScore", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function map(Request $request, Connection $connection)
    {
        $boundary = explode(',', $request->query->get('bbox'));

        $zoom = $request->query->get('zoom');

        $accessibilityLevels = $request->query->get('accessibilityLevels', []);
        $disabilitiesCategory = $request->query->get('disabilitiesCategory', AccessibilityScore::SCORE_CATEGORIES[0]);
        Assert::oneOf($disabilitiesCategory, AccessibilityScore::SCORE_CATEGORIES);

        $clusteringLevels = [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 2,
            5 => 2,
            6 => 3,
            7 => 3,
            8 => 3,
            9 => 3,
            10 => 4,
            11 => 5,
            12 => 5,
            13 => 5,
            14 => 6,
            15 => 6,
            16 => 7,
            17 => 8
        ];

        $precision = $clusteringLevels[$zoom] ?? 11;

        $q1 = $connection->createQueryBuilder()
            ->select([
                'COUNT(*) AS number',
                'ST_GEOHASH(point_value, :precision) AS hash',
                'ST_XMIN(ST_COLLECT(objects.point_value::GEOMETRY)) AS p1x',
                'ST_YMIN(ST_COLLECT(objects.point_value::GEOMETRY)) AS p1y',
                'ST_XMAX(ST_COLLECT(objects.point_value::GEOMETRY)) AS p2x',
                'ST_YMAX(ST_COLLECT(objects.point_value::GEOMETRY)) AS p2y',
                'ST_X(ST_CENTROID(ST_COLLECT(objects.point_value::GEOMETRY))) AS long',
                'ST_Y(ST_CENTROID(ST_COLLECT(objects.point_value::GEOMETRY))) AS lat'
            ])
            ->from('objects')
            ->andWhere('ST_Contains(ST_MAKEENVELOPE(:x1,:y1,:x2,:y2, 4326)::geometry, point_value::geometry)')
            ->andWhere('objects.deleted_at IS NULL')
            ->groupBy('hash')
            ->having('COUNT(*) > 1')
            ->setParameters([
                'x1' => $boundary[1],
                'y1' => $boundary[0],
                'x2' => $boundary[3],
                'y2' => $boundary[2],
                'precision' => $precision
            ]);

        if (count($accessibilityLevels)) {
            $q1->andWhere("overall_score_$disabilitiesCategory IN (:levels)")
                ->setParameter('levels', $accessibilityLevels, Connection::PARAM_STR_ARRAY);
        }

        $categories = $request->query->get('categories', []);
        if (count($categories)) {
            $q1->andWhere('category_id IN (:categories)')
                ->setParameter('categories', $categories, Connection::PARAM_STR_ARRAY);
        }
        $clusters = [];

        if ($zoom < 19) {
            $clusters = $q1->execute()->fetchAll();
        }

        $ids = array_column($clusters, 'hash');

        $q2 = $connection->createQueryBuilder()
            ->select([
                'objects.id',
                'categories.icon',
                "overall_score_$disabilitiesCategory as score",
                'ST_X(point_value::geometry) as long',
                'ST_Y(point_value::geometry) as lat',
            ])
            ->from('objects')
            ->leftJoin('objects', 'object_categories', 'categories', 'categories.id = objects.category_id')
            ->andWhere('ST_Contains(ST_MAKEENVELOPE(:x1,:y1,:x2,:y2, 4326)::geometry, point_value::geometry)')
            ->andWhere('objects.deleted_at IS NULL')
            ->andWhere('ST_GEOHASH(point_value, :precision) NOT IN (:ids)')
            ->setParameters([
                'x1' => $boundary[1],
                'y1' => $boundary[0],
                'x2' => $boundary[3],
                'y2' => $boundary[2],
                'ids' => array_merge($ids, ['']),
                'precision' => $precision
            ], [
                'ids' => Connection::PARAM_STR_ARRAY
            ]);

        if (count($accessibilityLevels)) {
            $q2->andWhere("overall_score_$disabilitiesCategory IN (:levels)")
                ->setParameter('levels', $accessibilityLevels, Connection::PARAM_STR_ARRAY);
        }

        if (count($categories)) {
            $q2->andWhere('category_id IN (:categories)')
                ->setParameter('categories', $categories, Connection::PARAM_STR_ARRAY);
        }
        $points = $q2->execute()->fetchAll();
        $pointsPrepared = array_map(function ($item) use ($connection) {
            $colors = [
                AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE => '#F8AC1A',
                AccessibilityScore::SCORE_NOT_ACCESSIBLE => '#DE1220',
                AccessibilityScore::SCORE_NOT_PROVIDED => '#7B95A7',
                AccessibilityScore::SCORE_UNKNOWN => '#7B95A7',
                AccessibilityScore::SCORE_FULL_ACCESSIBLE => '#3DBA3B'
            ];

            if (!isset($item['score'])) {
                $item['score'] = AccessibilityScore::SCORE_NOT_PROVIDED;
            }

            return [
                'id' => $item['id'],
                'coordinates' => [$connection->convertToPHPValue($item['lat'], 'float'), $connection->convertToPHPValue($item['long'], 'float')],
                'color' => $colors[$item['score']],
                'icon' => $item['icon'],
                'overallScore' => $item['score']
            ];
        }, $points);


        $clustersPrepared = array_map(function ($item) use ($connection) {
            return [
                'id' => $item['hash'],
                'coordinates' => [$connection->convertToPHPValue($item['lat'], 'float'), $connection->convertToPHPValue($item['long'], 'float')],
                'bbox' => [[$connection->convertToPHPValue($item['p1y'], 'float'), $connection->convertToPHPValue($item['p1x'], 'float')], [$connection->convertToPHPValue($item['p2y'], 'float'), $connection->convertToPHPValue($item['p2x'], 'float')]],
                'itemsCount' => $item['number'],
            ];
        }, $clusters);


        $clusters = [
            'clusters' => $clustersPrepared,
            'points' => $pointsPrepared,
        ];

        return new JsonResponse($clusters);
    }

    /**
     * @Route(path="/checkPresence", methods={"POST"})
     * @Post(
     *     path="/api/objects/checkPresence",
     *     summary="Проверка наличия объекта",
     *     tags={"Объекты"},
     *     @RequestBody(
     *         @JsonContent(
     *            @Property(property="name", type="string"),
     *            @Property(property="otherNames", type="string"),
     *         )
     *     ),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="name", type="boolean"),
     *             @Property(property="otherNames", type="boolean"),
     *         )
     *     )
     * )
     * @param PresenceRequestData $presenceRequestData
     */
    public function checkPresence(PresenceRequestData $presenceRequestData, Connection $connection)
    {
        $qb = $connection->createQueryBuilder()
            ->select('COUNT(*) > 0')
            ->from('objects')
            ->andWhere('deleted_at IS NULL');

        return [
            'name' => (clone $qb)->andWhere('title = :title')->setParameter('title', $presenceRequestData->name)->execute()->fetchColumn(),
            'otherNames' => (clone $qb)->andWhere('other_names = :otherNames')->setParameter('otherNames', $presenceRequestData->otherNames)->execute()->fetchColumn(),
        ];
    }
}
