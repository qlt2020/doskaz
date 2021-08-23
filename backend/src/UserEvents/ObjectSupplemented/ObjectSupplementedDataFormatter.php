<?php


namespace App\UserEvents\ObjectSupplemented;

use App\UserEvents\Context;
use App\UserEvents\Data;
use App\UserEvents\DataFormatter;
use Doctrine\DBAL\Connection;

class ObjectSupplementedDataFormatter implements DataFormatter
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
        return $data instanceof ObjectSupplementedData;
    }

    /**
     * @param Data|ObjectSupplementedData $data
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
            ->setParameter('userId', $data->userId)
            ->execute()->fetch(),
            $object
        );
    }
}
