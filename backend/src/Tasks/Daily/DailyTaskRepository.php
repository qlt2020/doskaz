<?php


namespace App\Tasks\Daily;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

class DailyTaskRepository
{
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(DailyTask::class);
    }

    public function add(DailyTask $task)
    {
        $this->entityManager->persist($task);
    }

    public function findLastByUserId(int $userId): ?DailyTask
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
        ], [
            'createdAt' => 'desc'
        ]);
    }

    public function findCurrentForUser(int $userId): ?DailyTask
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
            'completedAt' => null
        ]);
    }

    public function forAggregate(UuidInterface $id, callable $callback)
    {
        return $this->entityManager->transactional(function () use ($id, $callback) {
            $task = $this->repository->find($id, LockMode::PESSIMISTIC_WRITE);
            $callback($task);
        });
    }
}
