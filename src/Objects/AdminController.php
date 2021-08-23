<?php


namespace App\Objects;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Infrastructure\Doctrine\Flusher;
use App\RegionalCoordinators\RegionalCoordinatorCitiesFinder;
use App\RegionalCoordinators\RegionalCoordinatorRepository;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route(path="/api/admin/objects")
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @param RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder
     * @param RegionalCoordinatorRepository $regionalCoordinatorRepository
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function list(Request $request, Connection $connection, RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder, RegionalCoordinatorRepository $regionalCoordinatorRepository)
    {
        $filter = json_decode($request->query->get('filter', '{}'), true);

        $this->denyAccessUnlessGranted(AdminpanelPermission::OBJECTS_ACCESS);
        $queryBuilder = $connection->createQueryBuilder()
            ->select([
                'objects.id',
                'objects.title',
                'objects.address',
                'objects.created_at as "createdAt"',
                'object_categories.title as category',
                'cities.name as city'
            ])
            ->from('objects')
            ->leftJoin('objects', 'object_categories', 'object_categories', 'objects.category_id = object_categories.id')
            ->leftJoin('objects', 'cities_geometry', 'cities_geometry', 'ST_Contains(cities_geometry.geometry, objects.point_value::geometry)')
            ->leftJoin('cities_geometry', 'cities', 'cities', 'cities.id = cities_geometry.id')
            ->andWhere('objects.deleted_at IS NULL');

        if ($this->isGranted('ROLE_ADMIN')) {
            $queryBuilder->addSelect('COALESCE(users.full_name->>\'firstAndLast\', users.email, phone_credentials.number) as "createdBy"')
                ->leftJoin('objects', 'users', 'users', 'users.id = objects.created_by')
                ->leftJoin('objects', 'phone_credentials', 'phone_credentials', 'phone_credentials.id = objects.created_by');
        }

        foreach ($filter as $option => $value) {
            switch ($option) {
                case 'cityId':
                    if ($value !== 'all') {
                        $queryBuilder->andWhere('cities.id = :cityId')
                            ->setParameter('cityId', $value);
                    }
                    break;
                case 'title':
                    if ($value) {
                        $queryBuilder->andWhere('objects.title ilike :title')
                            ->setParameter('title', "%$value%");
                    }
                    break;
                case 'address':
                    if ($value) {
                        $queryBuilder->andWhere('objects.address ilike :address')
                            ->setParameter('address', "%$value%");
                    }
                    break;
                case 'categoryId':
                    if ($value !== 'all') {
                        $queryBuilder->andWhere('object_categories.parent_id = :categoryId')
                            ->setParameter('categoryId', $value);
                    }
                    break;
                case 'subCategoryId':
                    if ($value !== 'all') {
                        $queryBuilder->andWhere('objects.category_id = :subCategoryId')
                            ->setParameter('subCategoryId', $value);
                    }
                    break;
                case 'dateFrom':
                    if ($value) {
                        $queryBuilder->andWhere('objects.created_at::date >= :dateFrom')
                            ->setParameter('dateFrom', $value);
                    }
                    break;
                case 'dateTo':
                    if ($value) {
                        $queryBuilder->andWhere('objects.created_at::date <= :dateTo')
                            ->setParameter('dateTo', $value);
                    }
                    break;
            }
        }

        if ($regionalCoordinatorRepository->findByUserId($this->getUser()->id())) {
            $cities = $coordinatorCitiesFinder->find($this->getUser()->id());
            $queryBuilder->andWhere('
                EXISTS(
                    SELECT 1
                    FROM cities_geometry
                    WHERE cities_geometry.id IN (:cities)
                    AND ST_Contains(cities_geometry.geometry, objects.point_value::geometry)
                    LIMIT 1
                )
            ')->setParameter('cities', $cities, Connection::PARAM_INT_ARRAY);
        }

        $objects = (clone $queryBuilder)
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->orderBy('id', 'desc')
            ->execute()
            ->fetchAll();

        return [
            'items' => array_map(function ($object) use ($connection) {
                return array_replace($object, [
                    'createdAt' => $connection->convertToPHPValue($object['createdAt'], 'datetimetz_immutable')
                ]);
            }, $objects),
            'count' => $queryBuilder->select('count(*)')->execute()->fetchColumn()
        ];
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"}, requirements={"id" = "\d+"})
     * @param MapObject $mapObject
     * @param Flusher $flusher
     */
    public function delete(MapObject $mapObject, Flusher $flusher)
    {
        $this->denyAccessUnlessGranted(AdminpanelPermission::OBJECTS_ACCESS);
        $mapObject->markAsDeleted();
        $flusher->flush();
    }

    /**
     * @param MapObject $mapObject
     * @return MapObjectData
     * @Route(path="/{id}", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function retrieve(MapObject $mapObject)
    {
        $this->denyAccessUnlessGranted(AdminpanelPermission::OBJECTS_ACCESS);
        return $mapObject->toMapObjectData();
    }

    /**
     * @Route(path="/{id}", methods={"PUT"}, requirements={"id" = "\d+"})
     * @param MapObject $mapObject
     * @param MapObjectData $mapObjectData
     * @param MapObjectRepository $mapObjectRepository
     * @return void
     */
    public function update(MapObject $mapObject, MapObjectData $mapObjectData, MapObjectRepository $mapObjectRepository)
    {
        $this->denyAccessUnlessGranted(AdminpanelPermission::OBJECTS_ACCESS);
        $mapObjectRepository->forAggregate($mapObject->id(), function (MapObject $mapObject) use ($mapObjectData) {
            $mapObject->update($mapObjectData);
        });
    }

    /**
     * @Route(methods={"POST"}, requirements={"id" = "\d+"})
     * @param MapObjectData $mapObjectData
     * @param TokenStorageInterface $tokenStorage
     * @param MapObjectRepository $mapObjectRepository
     * @param Flusher $flusher
     * @return MapObjectData
     */
    public function create(MapObjectData $mapObjectData, TokenStorageInterface $tokenStorage, MapObjectRepository $mapObjectRepository, Flusher $flusher)
    {
        $this->denyAccessUnlessGranted(AdminpanelPermission::OBJECTS_ACCESS);
        $mapObject = MapObject::fromMapObjectRequestData($mapObjectData, $tokenStorage->getToken()->getUser()->id());
        $mapObjectRepository->add($mapObject);
        $flusher->flush();
        return $mapObject->toMapObjectData();
    }
}
