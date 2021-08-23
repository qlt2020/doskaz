<?php


namespace App\Tasks;

use Doctrine\ORM\EntityManagerInterface;

class TaskRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Task::class);
    }
}
