<?php


namespace App\Awards;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AwardRepository
{
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityManager->getRepository(Award::class);
    }

    public function add(Award $award)
    {
        $this->entityManager->persist($award);
    }

}
