<?php


namespace App\Objects\Verification;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class VerificationRepository
{
    private $entityManager;

    private $repository;

    /**
     * VerificationRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Verification::class);
    }

    public function add(Verification $verification)
    {
        $this->entityManager->persist($verification);
    }

    public function find(UuidInterface $uuid): ?Verification
    {
        return $this->repository->find($uuid);
    }
}
