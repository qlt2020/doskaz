<?php


namespace App\RegionalRepresentatives;

use Doctrine\ORM\EntityManagerInterface;

class RegionalRepresentativeRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(RegionalRepresentative::class);
    }

    public function add(RegionalRepresentative $regionalRepresentative)
    {
        $this->entityManager->persist($regionalRepresentative);
    }
}
