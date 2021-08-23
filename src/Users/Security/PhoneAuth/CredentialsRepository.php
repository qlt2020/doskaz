<?php


namespace App\Users\Security\PhoneAuth;

use Doctrine\ORM\EntityManagerInterface;

final class CredentialsRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Credentials::class);
    }

    public function add(Credentials $credentials)
    {
        $this->entityManager->persist($credentials);
    }

    public function findByPhoneNumber(string $phoneNumber): ?Credentials
    {
        return $this->repository->findOneBy([
            'number' => $phoneNumber
        ]);
    }

    public function find(int $id): ?Credentials
    {
        return $this->repository->find($id);
    }
}
