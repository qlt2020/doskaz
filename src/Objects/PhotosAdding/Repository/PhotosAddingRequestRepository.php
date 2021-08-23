<?php


namespace App\Objects\PhotosAdding\Repository;

use App\Objects\PhotosAdding\Entity\PhotosAddingRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class PhotosAddingRequestRepository
{
    private EntityManagerInterface $entityManager;

    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(PhotosAddingRequest::class);
    }

    public function add(PhotosAddingRequest $photosAddingRequest)
    {
        $this->entityManager->persist($photosAddingRequest);
    }
}
