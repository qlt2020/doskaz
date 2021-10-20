<?php


namespace App\Tasks\ProfileCompletion;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProfileCompletionTaskRepository
{
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(ProfileCompletionTask::class);
    }

    public function add(ProfileCompletionTask $completionTask)
    {
        $this->entityManager->persist($completionTask);
    }

    public function forAggregate(int $id, callable $callback)
    {
        return $this->entityManager->transactional(function () use ($id, $callback) {
            $task = $this->repository->findOneBy(['userId' => $id]);
            $callback($task);
        });
    }

    public function find(int $id): ?ProfileCompletionTask
    {
        return $this->repository->find($id);
    }

    public function findByUserId(int $userId): ?ProfileCompletionTask
    {
        return $this->repository->findOneBy(['userId' => $userId]);
    }
}
