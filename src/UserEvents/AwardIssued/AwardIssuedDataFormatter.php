<?php


namespace App\UserEvents\AwardIssued;

use App\UserEvents\Context;
use App\UserEvents\Data;
use App\UserEvents\DataFormatter;
use Doctrine\DBAL\Connection;

class AwardIssuedDataFormatter implements DataFormatter
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
        return $data instanceof AwardIssuedData;
    }

    /**
     * @param Data|AwardIssuedData $data
     * @return array
     */
    public function format(Data $data, Context $context): array
    {
        return $this->connection->createQueryBuilder()
            ->select([
                'title',
                'type'
            ])->from('awards')
            ->andWhere('id = :id')
            ->setParameter('id', $data->awardId)
            ->execute()
            ->fetch();
    }
}
