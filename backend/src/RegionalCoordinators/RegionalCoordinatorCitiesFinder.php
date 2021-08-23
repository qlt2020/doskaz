<?php


namespace App\RegionalCoordinators;

use Doctrine\DBAL\Connection;

class RegionalCoordinatorCitiesFinder
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $userId): array
    {
        return $this->connection->createQueryBuilder()
            ->select('cities.id')
            ->from('cities')
            ->andWhere('cities.id in (select jsonb_array_elements_text(cities)::int from regional_coordinators where user_id = :userId)')
            ->setParameter('userId', $userId)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }
}
