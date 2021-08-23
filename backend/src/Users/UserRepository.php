<?php


namespace App\Users;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

final class UserRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function find(int $id): ?User
    {
        return $this->repository->find($id);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findOneBy(array $criteria): ?User
    {
        return $this->repository->findOneBy($criteria);
    }

    public function forAggregate(int $id, callable $callback)
    {
        return $this->entityManager->transactional(function () use ($id, $callback) {
            $task = $this->repository->find($id, LockMode::PESSIMISTIC_WRITE);
            $callback($task);
        });
    }
}
