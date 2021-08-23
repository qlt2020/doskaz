<?php


namespace App\UserEvents;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserEventRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(UserEvent::class);
    }

    public function add(UserEvent $event)
    {
        $this->entityManager->persist($event);
    }
}
