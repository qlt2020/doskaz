<?php


namespace App\UserEvents\ObjectAdded;

use App\UserEvents\Context;
use App\UserEvents\Data;
use App\UserEvents\DataFormatter;
use Doctrine\DBAL\Connection;

class ObjectAddedDataFormatter implements DataFormatter
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function supports(Data $data): bool
    {
        return $data instanceof ObjectAddedData;
    }

    /**
     * @param Data|ObjectAddedData $data
     * @param Context $context
     * @return array|string[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function format(Data $data, Context $context): array
    {
        return $this->connection
            ->executeQuery("
            SELECT objects.id, objects.title, sub_category.title as \"categoryTitle\" FROM objects
            LEFT JOIN object_categories as sub_category ON sub_category.id = objects.category_id
            WHERE objects.uuid = :uuid LIMIT 1
            ", ['uuid' => $data->objectId])
            ->fetch();
    }
}
