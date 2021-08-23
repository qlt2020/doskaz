<?php


namespace App\RegionalCoordinators;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RegionalCoordinatorRepository
{
    private EntityManagerInterface $entityManager;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(RegionalCoordinator::class);
    }

    public function add(RegionalCoordinator $coordinator)
    {
        $this->entityManager->persist($coordinator);
    }

    public function remove(RegionalCoordinator $coordinator)
    {
        $this->entityManager->remove($coordinator);
    }

    public function findByUserId(int $userId): ?RegionalCoordinator
    {
        return $this->repository->findOneBy([
            'userId' => $userId
        ]);
    }
}
