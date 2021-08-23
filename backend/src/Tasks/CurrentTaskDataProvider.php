<?php


namespace App\Tasks;

use Doctrine\DBAL\Connection;

class CurrentTaskDataProvider
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function forUser(int $userId, int $cityId = 0): ?CurrentTaskData
    {
        $progress = $this->connection->createQueryBuilder()
            ->select([
                'progress'
            ])
            ->from('profile_completion_tasks')
            ->andWhere('user_id = :userId')
            ->setParameter('userId', $userId)
            ->execute()
            ->fetchColumn();

        if ($progress !== 100) {
            return new CurrentTaskData($progress, 'Заполните профиль', 4);
        }

        $administrationTask = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('administration_tasks')
            ->andWhere('administration_tasks.closed_at IS NULL')
            ->andWhere('NOT EXISTS(select 1 from user_administration_tasks where user_administration_tasks.user_id = :userId and user_administration_tasks.task_id = administration_tasks.id)')
            ->setParameter('userId', $userId)
            ->andWhere('administration_tasks.city_id = :cityId OR city_id is NULL')
            ->setParameter('cityId', $cityId)
            ->addOrderBy('created_at', 'desc')
            ->execute()
            ->fetch();

        if ($administrationTask) {
            return new CurrentTaskData(0, $administrationTask['name'], $administrationTask['points']);
        }

        $administrationTask = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('administration_tasks')
            ->andWhere('administration_tasks.closed_at IS NULL')
            ->andWhere('NOT EXISTS(select 1 from user_administration_tasks where user_administration_tasks.user_id = :userId and user_administration_tasks.task_id = administration_tasks.id)')
            ->setParameter('userId', $userId)
            ->andWhere('administration_tasks.city_id = :cityId OR city_id is NULL')
            ->setParameter('cityId', $cityId)
            ->addOrderBy('city_id', 'desc nulls last')
            ->execute()
            ->fetch();

        if($administrationTask) {
            return new CurrentTaskData(0, $administrationTask['name']);
        }

        $task = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('daily_verification_tasks')
            ->andWhere('user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->andWhere('completed_at is null')
            ->execute()
            ->fetch();
        if ($task) {
            return new CurrentTaskData(0, 'Верифицируйте 1 объект', $task['reward']);
        }

        $task = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('daily_tasks')
            ->andWhere('user_id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('completed_at is null')
            ->execute()
            ->fetch();

        if ($task) {
            return new CurrentTaskData(0, 'Добавьте 1 объект', $task['reward']);
        }

        return null;
    }
}
