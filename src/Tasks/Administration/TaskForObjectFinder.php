<?php


namespace App\Tasks\Administration;

use App\Cities\FindCityIdByLocation;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TaskForObjectFinder
{
    private Connection $connection;

    private FindCityIdByLocation $findCityIdByLocation;

    private AdministrationTaskRepository $administrationTaskRepository;

    public function __construct(Connection $connection, FindCityIdByLocation $findCityIdByLocation, AdministrationTaskRepository $administrationTaskRepository)
    {
        $this->connection = $connection;
        $this->findCityIdByLocation = $findCityIdByLocation;
        $this->administrationTaskRepository = $administrationTaskRepository;
    }

    public function find(UuidInterface $objectId): ?AdministrationTask
    {
        $objectData = $this->connection->createQueryBuilder()
            ->addSelect('category_id as sub_category_id')
            ->addSelect('ST_Y(point_value::geometry) as lat')
            ->addSelect('ST_X(point_value::geometry) as lon')
            ->addSelect('object_sub_categories.parent_id as category_id')
            ->addSelect('created_by')
            ->from('objects')
            ->join('objects', 'object_categories', 'object_sub_categories', 'object_sub_categories.id = objects.category_id')
            ->andWhere('objects.uuid = :objectId')
            ->setParameter('objectId', $objectId)
            ->execute()
            ->fetch();

        $cityId = $this->findCityIdByLocation->execute($objectData['lat'], $objectData['lon']);

        if (is_null($cityId) || is_null($objectData['created_by'])) {
            return null;
        }

        $task = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('administration_tasks')
            ->andWhere("
                (
                    (area IS NOT NULL AND area && ST_MAKEPOINT(:x, :y) AND sub_category_id = :subCategoryId)
                    OR
                    (area IS NOT NULL AND area && ST_MAKEPOINT(:x, :y) AND sub_category_id IS NULL AND category_id = :categoryId)
                    OR
                    (area IS NOT NULL AND area && ST_MAKEPOINT(:x, :y) AND sub_category_id IS NULL AND category_id IS NULL)
                    OR
                    (city_id IS NOT NULL AND city_id = :cityId AND sub_category_id = :subCategoryId)
                    OR
                    (city_id IS NOT NULL AND city_id = :cityId AND sub_category_id IS NULL AND category_id = :categoryId)
                    OR
                    (city_id IS NULL AND sub_category_id = :subCategoryId)
                    OR
                    (city_id IS NULL AND sub_category_id IS NULL AND category_id = :categoryId)
                    OR
                    (city_id IS NOT NULL AND city_id = :cityId)
                    OR
                    (area IS NULL AND city_id IS NULL AND sub_category_id IS NULL AND category_id IS NULL)
                )
            ")
            ->andWhere('NOT EXISTS (SELECT 1 FROM user_administration_tasks WHERE user_administration_tasks.task_id = administration_tasks.id AND user_administration_tasks.user_id = :userId)')
            ->andWhere('closed_at IS NULL')
            ->setParameter('x', $objectData['lon'])
            ->setParameter('y', $objectData['lat'])
            ->setParameter('subCategoryId', $objectData['sub_category_id'])
            ->setParameter('categoryId', $objectData['category_id'])
            ->setParameter('userId', $objectData['created_by'])
            ->setParameter('cityId', $cityId)
            ->setMaxResults(1)
            ->addOrderBy('created_at', 'desc')
            ->execute()
            ->fetch();

        if ($task) {
            return $this->administrationTaskRepository->find(Uuid::fromString($task['id']));
        }
        return null;
    }
}
