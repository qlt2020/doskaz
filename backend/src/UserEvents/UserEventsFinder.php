<?php


namespace App\UserEvents;

use Doctrine\DBAL\Connection;

class UserEventsFinder
{
    private Connection $connection;

    private iterable $formatters;

    /**
     * UserEventsFinder constructor.
     * @param Connection $connection
     * @param DataFormatter[] $formatters
     */
    public function __construct(Connection $connection, $formatters)
    {
        $this->connection = $connection;
        $this->formatters = $formatters;
    }

    public function execute(int $userId, int $page, string $orderField, string $orderDirection): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('id', 'data', 'date', 'user_id as "userId"')
            ->from('user_events')
            ->andWhere('user_id = :userId')
            ->setParameter('userId', $userId);


        $items = (clone $qb)->orderBy($orderField, $orderDirection)
            ->setMaxResults(10)
            ->setFirstResult(($page - 1) * 10)
            ->execute()
            ->fetchAll();

        $items = array_map(function ($item) {
            try {
                $data = $this->connection->convertToPHPValue($item['data'], Data::class);
            } catch (\Exception $exception) {
                return [];
            }

            $result = [
                'userId' => $item['userId'],
                'date' => $this->connection->convertToPHPValue($item['date'], 'datetimetz_immutable'),
                'type' => array_flip(Data::DISCRIMINATOR_MAP)[get_class($data)]
            ];

            foreach ($this->formatters as $formatter) {
                if ($formatter->supports($data)) {
                    $result['data'] = $formatter->format($data, new Context($item['userId']));
                }
            }
            return $result;
        }, $items);

        return [
            'items' => $items
        ];
    }

    public function findAll(int $limit): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('user_events.id', 'user_events.data', 'user_events.date', 'user_events.user_id as "userId"', 'users.full_name->>\'firstAndLast\' as username')
            ->from('user_events')
            ->leftJoin('user_events', 'users', 'users', 'users.id = user_events.user_id');

        $items = (clone $qb)->orderBy('date', 'desc')
            ->setMaxResults($limit)
            ->execute()
            ->fetchAll();

        $items = array_map(function ($item) {
            try {
                $data = $this->connection->convertToPHPValue($item['data'], Data::class);
            } catch (\Exception $exception) {
                return [];
            }

            $result = [
                'username' => $item['username'],
                'userId' => $item['userId'],
                'date' => $this->connection->convertToPHPValue($item['date'], 'datetimetz_immutable'),
                'type' => array_flip(Data::DISCRIMINATOR_MAP)[get_class($data)]
            ];

            foreach ($this->formatters as $formatter) {
                if ($formatter->supports($data)) {
                    $result['data'] = $formatter->format($data, new Context($item['userId']));
                }
            }
            return $result;
        }, $items);

        return $items;
    }
}
