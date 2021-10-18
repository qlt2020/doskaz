<?php


namespace App\Tasks;

use Doctrine\DBAL\Connection;
use Symfony\Contracts\Translation\TranslatorInterface;

class CurrentTaskDataProvider
{
    /**
     * @var Connection
     */
    private Connection $connection;
    private TranslatorInterface $translator;

    public function __construct(Connection $connection, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->translator = $translator;
    }

    public function forUser(int $userId, int $cityId = 0, string $lang): ?CurrentTaskData
    {
        $profile = $this->translator->trans('Заполните профиль', [], 'attributes', $lang);
        $verification = $this->translator->trans('Верифицируйте 1 объект', [], 'attributes', $lang);
        $addition = $this->translator->trans('Добавьте 1 объект', [], 'attributes', $lang);
        
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
            return new CurrentTaskData($progress, $profile, 4);
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

            if ($administrationTask) {
                return new CurrentTaskData(0, $administrationTask['name'], $administrationTask['points']);
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
            return new CurrentTaskData(0, $verification, $task['reward']);
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
            return new CurrentTaskData(0, $addition, $task['reward']);
        }

        return null;
    }
}
