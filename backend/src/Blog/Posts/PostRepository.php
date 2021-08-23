<?php


namespace App\Blog\Posts;

use Doctrine\ORM\EntityManagerInterface;

final class PostRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function add(Post $post)
    {
        $this->entityManager->persist($post);
    }
}
