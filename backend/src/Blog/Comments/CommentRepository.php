<?php


namespace App\Blog\Comments;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

class CommentRepository
{
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Comment::class);
    }

    public function add(Comment $comment)
    {
        $this->entityManager->persist($comment);
    }

    public function find(UuidInterface $id, $lockMode = null): ?Comment
    {
        return $this->repository->find($id, $lockMode);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    public function delete(Comment $comment)
    {
        $this->entityManager->remove($comment);
    }
}
