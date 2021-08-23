<?php


namespace App\UserEvents\ObjectReviewed;

use App\UserEvents\Context;
use App\UserEvents\Data;
use App\UserEvents\DataFormatter;
use Doctrine\DBAL\Connection;

class ObjectReviewedDataFormatter implements DataFormatter
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function supports(Data $data): bool
    {
        return $data instanceof ObjectReviewedData;
    }

    /**
     * @param Data|ObjectReviewedData $data
     * @return array
     */
    public function format(Data $data, Context $context): array
    {
        $object = $this->connection->createQueryBuilder()
            ->select('title, id')
            ->from('objects')
            ->andWhere('uuid = :uuid')
            ->setParameter('uuid', $data->objectId)
            ->execute()
            ->fetch();

        return array_merge(
            $this->connection->createQueryBuilder()
            ->select('full_name->>\'firstAndLast\' as username, id as "userId"')
            ->from('users')
            ->andWhere('id = :userId')
            ->setParameter('userId', $data->reviewerId)
            ->execute()->fetch(),
            $object
        );
    }
}
