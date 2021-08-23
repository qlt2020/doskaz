<?php


namespace App\Tasks\DailyVerification;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Ramsey\Uuid\UuidInterface;

class DailyVerificationTaskRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(DailyVerificationTask::class);
        $this->entityManager = $entityManager;
    }

    public function add(DailyVerificationTask $task)
    {
        $this->entityManager->persist($task);
    }

    public function hasCompletedForUser(UuidInterface $objectId, int $userId): bool
    {
        $task = $this->repository->findOneBy([
            'objectId' => $objectId,
            'userId' => $userId
        ]);

        return !empty($task);
    }

    public function forAggregate(UuidInterface $id, callable $callback)
    {
        return $this->entityManager->transactional(function () use ($id, $callback) {
            $task = $this->repository->find($id, LockMode::PESSIMISTIC_WRITE);
            $callback($task);
        });
    }

    public function findCurrentForUser(int $userId): ?DailyVerificationTask
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
            'completedAt' => null
        ]);
    }
}
