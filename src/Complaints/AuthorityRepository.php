<?php


namespace App\Complaints;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AuthorityRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(ComplaintAuthority::class);
        $this->entityManager = $entityManager;
    }

    public function add(ComplaintAuthority $authority)
    {
        $this->entityManager->persist($authority);
    }

    public function count(array $criteria)
    {
        return $this->repository->count($criteria);
    }
}
