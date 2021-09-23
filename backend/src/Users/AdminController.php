<?php


namespace App\Users;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Infrastructure\Doctrine\Flusher;
use App\Users\Services\ExportToExcelService;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Json;
use Webmozart\Assert\Assert;

/**
 * @Route(path="/api")
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/admin/profile", methods={"GET"})
     * @param TokenStorageInterface $tokenStorage
     * @param Connection $connection
     * @return array
     */
    public function me(TokenStorageInterface $tokenStorage, Connection $connection)
    {
        $this->denyAccessUnlessGranted('adminpanel_access');

        $user = $connection->createQueryBuilder()
            ->select('users.id', 'full_name->>\'firstAndLast\' as name', 'email', 'phone_credentials.number as phone', 'roles', 'users.created_at as "createdAt"')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id')
            ->andWhere('users.id = :id')
            ->setParameter('id', $tokenStorage->getToken()->getUser()->id())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        return array_replace($user, [
            'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
            'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable'),
            'permissions' => array_filter(AdminpanelPermission::ALL, function ($permission) {
                return $this->isGranted($permission);
            })
        ]);
    }

    /**
     * @Route(path="/users", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function list(Request $request, Connection $connection)
    {
        $filter = json_decode($request->query->get('filter', '{}'), true);

        $usersQb = $connection->createQueryBuilder()
            ->select('users.id', 'full_name->>\'firstAndLast\' as name', 'email',
                'phone_credentials.number as phone', 'roles', 'users.created_at as "createdAt"','category', 'gender',
                'c.name as city', 'birthday')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials',
                'users.id = phone_credentials.id')
            ->leftJoin('users', 'cities', 'c', 'users.city_id = c.id');

        foreach ($filter as $key => $value) {
            if (empty($value)) {
                continue;
            }
            switch ($key) {
                case 'email':
                    $usersQb->orWhere('email ilike :email')
                        ->setParameter('email', "%$value%");
                    break;
                case 'phone':
                    $usersQb->orWhere('phone_credentials.number ilike :phone')
                        ->setParameter('phone', "%$value%");
                    break;
            }
        }

        $count = (clone $usersQb)->select('count(*)')->execute()->fetchColumn();

        $items = array_map(function ($user) use ($connection) {
            return array_replace($user, [
                'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
                'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable')
            ]);
        }, $usersQb->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset'))->orderBy('id', 'desc')
            ->execute()->fetchAll());

        return [
            'items' => $items,
            'count' => $count
        ];
    }

    /**
     * @Route(path="/users/{id}", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param int $id
     * @param Connection $connection
     */
    public function user(int $id, Connection $connection)
    {
        try {
            $user = $connection->createQueryBuilder()
                ->select('users.id', 'users.name', 'email', 'phone_credentials.number as phone', 'roles',
                    'users.created_at as "createdAt"', 'category', 'gender', 'c.name as city', 'birthday')
                ->from('users')
                ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id')
                ->leftJoin('users', 'cities', 'c', 'users.city_id = c.id')
                ->andWhere('users.id = :id')
                ->setParameter('id', $id)
                ->execute()
                ->fetch();

            if (!$user) {
                throw new NotFoundHttpException();
            }

            return array_replace($user, [
                'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
                'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable')
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @Route(path="/users/{id}", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param User $user
     * @param UserData $data
     * @param Flusher $flusher
     */
    public function update(User $user, UserData $data, Flusher $flusher)
    {
        $user->update($data);
        $flusher->flush();
    }

    /**
     * @Route(path="/admin/users/statistics", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/admin/users/statistics",
     *     summary="Статистика по пользователям",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="city_id", in="query", description="Id города", @Schema(type="integer", nullable=true)),
     *     @Parameter(name="category", in="query", description="Категория", @Schema(type="string", nullable=true)),
     *     @Response(response=200, description=""),
     * )
     */
    public function statistics(Connection $connection, Request $request)
    {
        $query = $connection->createQueryBuilder()->from('cities')
            ->leftJoin('cities', 'users', 'users', 'users.city_id = cities.id');

        if ($request->query->has('category')) {
            $categories = [$request->query->getAlpha('category')];
        } else {
            $categories = User::USER_CATEGORIES;
        }

        foreach ($categories as $category) {
            if ($category === User::CATEGORY_UNDEFINED) {
                $query->addSelect("SUM (CASE WHEN users.category is NULL and users.gender = 'u' THEN 1 ELSE 0 END) AS {$category}_unknown");
                $query->addSelect("SUM (CASE WHEN users.category is NULL and users.gender = 'm' THEN 1 ELSE 0 END) AS {$category}_men");
                $query->addSelect("SUM (CASE WHEN users.category is NULL and users.gender = 'f' THEN 1 ELSE 0 END) AS {$category}_women");
            } else {
                $query->addSelect("SUM (CASE WHEN users.category = '{$category}' and users.gender = 'u' THEN 1 ELSE 0 END) AS {$category}_unknown");
                $query->addSelect("SUM (CASE WHEN users.category = '{$category}' and users.gender = 'm' THEN 1 ELSE 0 END) AS {$category}_men");
                $query->addSelect("SUM (CASE WHEN users.category = '{$category}' and users.gender = 'f' THEN 1 ELSE 0 END) AS {$category}_women");
            }
        }

        $query->addSelect("SUM (CASE WHEN users.gender = 'u' THEN 1 ELSE 0 END) AS total_unknown");
        $query->addSelect("SUM (CASE WHEN users.gender = 'm' THEN 1 ELSE 0 END) AS total_men");
        $query->addSelect("SUM (CASE WHEN users.gender = 'f' THEN 1 ELSE 0 END) AS total_women");

        if ($request->query->has('city_id')) {
            $query->where("cities.id = {$request->query->getInt('city_id')}");
        } else {
            $rkQuery = clone $query;
        }

        $fetched = $query->addSelect('cities.name')
                            ->addSelect('cities.id')
                            ->groupBy('cities.name')
                            ->addGroupBy('cities.id')
                            ->addOrderBy('case when cities.id = 106724 then 0 else 3 END', 'ASC')
                            ->addOrderBy('case when cities.id = 158106 then 0 else 3 END', 'ASC')
                            ->addOrderBy('case when cities.id = 178771 then 0 else 3 END', 'ASC')
                            ->addOrderBy('case when cities.id in (110170,68402,26551,212922,155241,125193,165288,9103,33335,79497,168533,182036,
                                51071,31223,113407) then 1 ELSE 2 END, cities.name', 'ASC')
                            ->execute()->fetchAllAssociative();
        
        if (isset($rkQuery)) {
            $rkFetched = $rkQuery->execute()->fetchAssociative();
            $rkFetched['name'] = 'РК';
            array_unshift($fetched, $rkFetched);
        }

        return array_map(function ($city) use ($categories) {
            $array = ['name' => $city['name']];
            $array['total'] = [
                'men' => $city['total_men'],
                'women' => $city['total_women'],
                'unknown' => $city['total_unknown']
            ];
            $array['total']['total'] = array_sum($array['total']);
            $array['categories'] = [];
            foreach ($categories as $category) {
                $array['categories'][$category] = [
                    'men' => $city[strtolower($category . '_men')],
                    'women' => $city[strtolower($category . '_women')],
                    'unknown' => $city[strtolower($category . '_unknown')],
                ];
                $array['categories'][$category]['total'] = array_sum($array['categories'][$category]);
                $array['categories'][$category]['name'] = $category;
            }

            return $array;
        }, $fetched);
    }

    /**
     * @Route(path="/dashboard/users/statistics", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/dashboard/users/statistics",
     *     summary="Статистика по кол-ву пользователей в разрезе категории",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=200, description=""),
     * )
     */
    public function dashboardUserStatistics(Connection $connection)
    {
        try {
            $query = $connection->createQueryBuilder();
            $categories = User::USER_CATEGORIES;

            foreach ($categories as $category) {
                $query->addSelect("SUM (CASE WHEN users.category = '{$category}' THEN 1 ELSE 0 END) AS {$category}");
            }
            $query->addSelect('count(*) as registered');
            $query->addSelect('SUM (CASE WHEN users.gender = \'m\' THEN 1 ELSE 0 END) AS men');
            $query->addSelect('SUM (CASE WHEN users.gender = \'f\' THEN 1 ELSE 0 END) AS women');
            $query = $query->from('users')
                ->execute()
                ->fetchAll();

            $data = [];
            foreach ($query[0] as $key => $value) {
               if(in_array($key, array_map('strtolower', $categories))){
                   $data['categories'][$key] = $value;
               }else{
                   $data[$key] = $value;
               }
            }

            return new JsonResponse($data);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @Route(path="/dashboard/users/age-statistics", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/dashboard/users/age-statistics",
     *     summary="Статистика по возрасту в разрезе категории",
     *     @Parameter(name="category", in="query", description="Категория", @Schema(type="string", nullable=true)),
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=200, description=""),
     * )
     */
    public function dashboardCategoryByAges(Connection $connection, Request $request)
    {
        try {
            $query = $connection->createQueryBuilder()
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'18\' and \'23\' THEN 1 ELSE 0 END) AS from_18_to_23')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'23\' and \'28\' THEN 1 ELSE 0 END) AS from_23_to_28')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'28\' and \'33\' THEN 1 ELSE 0 END) AS from_28_to_33')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'33\' and \'38\' THEN 1 ELSE 0 END) AS from_33_to_38')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'38\' and \'43\' THEN 1 ELSE 0 END) AS from_38_to_43')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'43\' and \'50\' THEN 1 ELSE 0 END) AS from_43_to_50')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'50\' and \'100\' THEN 1 ELSE 0 END) AS from_50_to_100')
                ->from('users');

            if ($request->query->has('category')) {
                Assert::oneOf($request->query->getAlpha('category'), User::USER_CATEGORIES);
                $query->where("users.category = '{$request->query->getAlpha('category')}'");
            }

            return new JsonResponse($query->execute()->fetchAll());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], 400);
        }
    }


    /**
     * @Route(path="/user/statistics/export/excel", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/user/statistics/export/excel",
     *     summary="Экспорт статистики по пользователям",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="category", in="query", description="Категория пользователя", @Schema(type="string", nullable=true)),
     *     @Parameter(name="city_id", in="query", description="Id города", @Schema(type="integer", nullable=true)),
     *     @Response(response=200, description=""),
     * )
     */
    public function exportToExcel(Connection $connection, Request $request) {
        $data = $this->statistics($connection, $request);
        $export = new ExportToExcelService();
        $export->fillData($data);

        try {
            $filePath = $export->writeFile();
            $file = new File($filePath);
            return $this->file($file, 'Статистика по пользователям.xlsx')->deleteFileAfterSend();
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }

}
