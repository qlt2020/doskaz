<?php


namespace App\Objects\Reviews;

use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Review::class);
        $this->entityManager = $entityManager;
    }

    public function add(Review $review)
    {
        $this->entityManager->persist($review);
    }
}
