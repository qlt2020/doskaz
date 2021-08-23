<?php


namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
