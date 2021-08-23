<?php


namespace App\Tasks;

use Doctrine\DBAL\Connection;

class UserTasksFinder
{
    private Connection $connection;
    /**
     * @var CurrentTaskDataProvider
     */
    private CurrentTaskDataProvider $currentTaskDataProvider;

    public function __construct(Connection $connection, CurrentTaskDataProvider $currentTaskDataProvider)
    {
        $this->connection = $connection;
        $this->currentTaskDataProvider = $currentTaskDataProvider;
    }

    public function findForUser(int $userId, int $cityId, int $page, string $sort, int $perPage = 10): array
    {
        $currentTask = $this->currentTaskDataProvider->forUser($userId, $cityId);

        $query = "
               select completed_at, 'Заполните профиль' as type, 4 as points, users.created_at, 0 as priority
                  from profile_completion_tasks
                  join users on users.id = profile_completion_tasks.user_id
                  where user_id = :userId
                  and completed_at IS NOT NULL
               union all select completed_at, 'Добавьте 1 объект' as type, reward as points, created_at, 0 as priority from daily_tasks where user_id = :userId  and completed_at IS NOT NULL
               union all select completed_at, 'Верифицируйте 1 объект' as type, reward as points, created_at, 0 as priority from daily_verification_tasks where user_id = :userId  and completed_at IS NOT NULL
               union all select user_administration_tasks.created_at as completed_at, administration_tasks.name as type, user_administration_tasks.points, administration_tasks.created_at, 0 as priority from user_administration_tasks
                  join administration_tasks on user_administration_tasks.task_id = administration_tasks.id
                  where user_administration_tasks.user_id = :userId
        ";

        if ($currentTask) {
            $query  = $query. "
                union all select null as completed_at, :currentTaskTitle as type, :currentTaskPoints as points, CURRENT_TIMESTAMP(0) as created_at, 1 as priority
            ";
        }

        [$field, $sort] = explode(' ', $sort);

        $qb = $this->connection->createQueryBuilder()
            ->select(
                'completed_at as "completedAt"',
                'created_at as "createdAt"',
                'type as title',
                'points'
            )
            ->from("($query)", 'tasks')
            ->setParameter('userId', $userId);

        if ($currentTask) {
            $qb->setParameter('currentTaskTitle', $currentTask->title);
            $qb->setParameter('currentTaskPoints', $currentTask->pointsReward);
        }

        $tasks = (clone $qb)->orderBy('"' . $field . '"', $sort)
        ->setMaxResults($perPage)
        ->addOrderBy('priority', 'desc')
        ->setFirstResult(($page - 1) * $perPage)
        ->execute()
        ->fetchAll();

        return [
            'pages' => (clone $qb)->select('CEIL(count(*)::FLOAT / :perPage)::INT')->setParameter('perPage', $perPage)->execute()->fetchColumn(),
            'items' => array_map(function ($task) {
                return array_replace($task, [
                    'createdAt' => $this->connection->convertToPHPValue($task['createdAt'], 'datetimetz_immutable')
                ]);
            }, $tasks)
        ];
    }
}
