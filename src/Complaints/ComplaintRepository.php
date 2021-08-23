<?php
declare(strict_types=1);

namespace App\Complaints;

use Doctrine\ORM\EntityManagerInterface;

final class ComplaintRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Complaint $complaint)
    {
        $this->entityManager->persist($complaint);
    }
}
