<?php


namespace App\Objects\Adding;

use Doctrine\ORM\EntityManagerInterface;

class AddingRequestRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(AddingRequest::class);
    }

    public function add(AddingRequest $addingRequest)
    {
        $this->entityManager->persist($addingRequest);
    }
}
