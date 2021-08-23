<?php


namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

class Transactional
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transaction(callable $fn)
    {
        return $this->entityManager->transactional($fn);
    }
}
