<?php


namespace App\Tasks\Administration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserAdministrationTaskRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(UserAdministrationTask::class);
    }

    public function add(UserAdministrationTask $administrationTask)
    {
        $this->entityManager->persist($administrationTask);
    }
}
