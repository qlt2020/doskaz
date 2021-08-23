<?php


namespace App\Users\Security;

use Doctrine\ORM\EntityManagerInterface;

final class AccessTokenRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(AccessToken::class);
    }

    public function add(AccessToken $accessToken)
    {
        $this->entityManager->persist($accessToken);
    }

    public function remove(AccessToken $accessToken)
    {
        $this->entityManager->remove($accessToken);
    }

    public function find(string $value): ?AccessToken
    {
        return $this->repository->find($value);
    }
}
