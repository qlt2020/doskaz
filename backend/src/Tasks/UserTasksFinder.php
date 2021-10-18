<?php


namespace App\Tasks;

use Doctrine\DBAL\Connection;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserTasksFinder
{
    private Connection $connection;
    /**
     * @var CurrentTaskDataProvider
     */
    private CurrentTaskDataProvider $currentTaskDataProvider;
    private TranslatorInterface $translator;

    public function __construct(Connection $connection, CurrentTaskDataProvider $currentTaskDataProvider, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->currentTaskDataProvider = $currentTaskDataProvider;
        $this->translator = $translator;
    }

    public function findForUser(int $userId, int $cityId, int $page, string $sort, string $lang, int $perPage = 10): array
    {
        $currentTask = $this->currentTaskDataProvider->forUser($userId, $lang, $cityId);

        $profile = $this->translator->trans('Заполните профиль', [], 'attributes', $lang);
        $verification = $this->translator->trans('Верифицируйте 1 объект', [], 'attributes', $lang);
        $addition = $this->translator->trans('Добавьте 1 объект', [], 'attributes', $lang);

        $query = "
               select completed_at, '$profile' as type, 4 as points, users.created_at, 0 as priority
                  from profile_completion_tasks
                  join users on users.id = profile_completion_tasks.user_id
                  where user_id = :userId
                  and completed_at IS NOT NULL
               union all select completed_at, '$addition' as type, reward as points, created_at, 0 as priority from daily_tasks where user_id = :userId  and completed_at IS NOT NULL
               union all select completed_at, '$verification' as type, reward as points, created_at, 0 as priority from daily_verification_tasks where user_id = :userId  and completed_at IS NOT NULL
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
