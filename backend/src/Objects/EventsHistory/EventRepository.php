<?php


namespace App\Objects\EventsHistory;

use Doctrine\ORM\EntityManagerInterface;

class EventRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Event::class);
    }

    public function add(Event $event)
    {
        $this->entityManager->persist($event);
    }
}
