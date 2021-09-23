<?php


namespace App\Levels;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LevelRepository
{
    private EntityManagerInterface $entityManager;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Level::class);
    }

    public function add(Level $level)
    {
        $this->entityManager->persist($level);
    }

    public function forAggregate(int $id, callable $callback)
    {
        return $this->entityManager->transactional(function () use ($id, $callback) {
            $task = $this->repository->findOneBy(['userId' => $id]);
            $callback($task);
        });
    }

    public function find($id): ?Level
    {
        return $this->repository->findOneBy(['userId' => $id]);
    }
}
