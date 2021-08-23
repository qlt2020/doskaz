<?php


namespace App\Users\CommentsHistory;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class CommentHistoryRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(CommentHistory::class);
    }

    public function add(CommentHistory $history)
    {
        $this->entityManager->persist($history);
    }

    public function find(UuidInterface $id): ?CommentHistory
    {
        return $this->repository->find($id);
    }
}
