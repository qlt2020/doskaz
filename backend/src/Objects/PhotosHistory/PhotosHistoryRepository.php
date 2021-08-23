<?php


namespace App\Objects\PhotosHistory;

use Doctrine\ORM\EntityManagerInterface;

class PhotosHistoryRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(PhotosHistory::class);
    }

    public function add(PhotosHistory $history)
    {
        $this->entityManager->persist($history);
    }

    public function remove(PhotosHistory $history)
    {
        $this->entityManager->remove($history);
    }

    /**
     * @param array $criteria
     * @return PhotosHistory[]
     */
    public function findBy(array $criteria): array
    {
        return $this->repository->findBy($criteria);
    }
}
