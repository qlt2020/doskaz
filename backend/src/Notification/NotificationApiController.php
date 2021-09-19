<?php

namespace App\Notification;

use App\Objects\Category;
use App\Users\Subscribe\UserSubscribe;
use App\Users\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use OpenApi\Annotations\RequestBody;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route(path="/api/notifications")
 */
class NotificationApiController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/list", methods={"GET"})
     * @param Connection $connection
     * @return JsonResponse
     * @Get(
     *     path="/api/notifications/list",
     *     tags={"Уведомления"},
     *     security={{"clientAuth": {}}},
     *     summary="Список уведомлении",
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/Notification")
     *         )
     *     }
     * )
     */
    public function index(Connection $connection, Request $request)
    {
        $page = $request->query->getInt('page',1);
        $perPage = $request->query->getInt('perPage',7);
        try {
            $queryBuilder = $connection->createQueryBuilder()
                ->select('n.id as id')
                ->addSelect('data->>\'title\' as title')
                ->addSelect('o.address')
                ->addSelect('o.description')
                ->addSelect('o.id as object_id')
                ->addSelect('o.title as object_name')
                ->addSelect("ocp.id as category_id")
                ->addSelect('(select name from cities where id = (SELECT id from cities_geometry where st_contains(cities_geometry.geometry, 
ST_SetSRID(st_point(ST_X(ST_CENTROID(ST_COLLECT(o.point_value::GEOMETRY))),
ST_Y(ST_CENTROID(ST_COLLECT(o.point_value::GEOMETRY)))), 4326)))) as city')
                ->addSelect('ocp.title as category_name')
                ->addSelect( 'ocp.icon as category_icon')
                ->addSelect('oc.title as sub_category_name')
                ->addSelect('n.created_at')
                ->from('notifications', 'n')
                ->join('n', 'objects', 'o', 'data->>\'objectId\' = o.uuid::text')
                ->join('n', 'object_categories', 'oc', 'oc.id = o.category_id')
                ->join('oc', 'object_categories', 'ocp', 'oc.parent_id = ocp.id')
                ->where('n.user_id = :user_id')
                ->setParameters([
                    'user_id' => $this->getUser()->id(),
                ])
                ->groupBy('n.id','o.id', 'ocp.id','oc.id');


            $count = count((clone $queryBuilder)->execute()->fetchAll());

            $items = $queryBuilder->setMaxResults($perPage)
                ->orderBy('n.created_at', 'desc')
                ->setFirstResult(($page - 1) * $perPage)->execute()->fetchAll();

            return new JsonResponse([
                'items' => $items,
                'pages' => ceil($count / $perPage)
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/subscribe", methods={"POST"})
     * @param SubscribeData $data
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @Post (
     *     path="/api/notifications/subscribe",
     *     tags={"Уведомления"},
     *     security={{"clientAuth": {}}},
     *     summary="Подписаться на уведомления",
     *      @RequestBody(required=true, description="Уведомление", @JsonContent(ref="#/components/schemas/Subscribe")),
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/Notification")
     *         )
     *     }
     * )
     */
    public function subscribe(SubscribeData $data, EntityManagerInterface $entityManager)
    {
        try {
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->id());
            // set parent level to accessibility level category
            $data->content->category = $user->getAccessibilityGroup($data->content->category);

            if(is_null($data->content->category)) {
                return new JsonResponse([
                    'status' => false,
                    'message' => 'Категория не найдена'
                ], 400);
            }

            if (count($user->getSubscribes()) > 0) {
                $userSubscribe = $user->getSubscribes()[0];
                $userSubscribe->setData($data);
            } else {
                $userSubscribe = new UserSubscribe($user, $data, UserSubscribe::STATUS_ACTIVE);
                $entityManager->persist($userSubscribe);
            }
            $entityManager->flush();

            return new JsonResponse([
                'status' => true,
                'message' => 'Вы успешно подписаны на обновление'
            ]);

        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/unsubscribe", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @Get (
     *     path="/api/notifications/unsubscribe",
     *     tags={"Уведомления"},
     *     security={{"clientAuth": {}}},
     *     summary="Отписаться на уведомления",
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/Notification")
     *         )
     *     }
     * )
     */
    public function unsubscribe(EntityManagerInterface $entityManager)
    {
        try {
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->id());
            if (count($user->getSubscribes()) > 0) {
                foreach ($user->getSubscribes() as $subscribe) {
                    $entityManager->remove($subscribe);
                }
                $entityManager->flush();
            }else{
                return new JsonResponse([
                    'status' => true,
                    'message' => 'У вас нет активных подписок'
                ],422);
            }

            return new JsonResponse([
                'status' => true,
                'message' => 'Вы успешно отписались от уведомлении'
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}