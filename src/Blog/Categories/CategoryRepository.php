<?php
declare(strict_types=1);

namespace App\Blog\Categories;

use Doctrine\ORM\EntityManagerInterface;

final class CategoryRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Category::class);
    }

    public function add(Category $category)
    {
        $this->entityManager->persist($category);
    }

    public function existsBySlug(string $slug): bool
    {
        return (bool)$this->repository->findOneBy(['slug.value' => $slug]);
    }

    public function find(int $id): ?Category
    {
        return $this->repository->find($id);
    }
}
