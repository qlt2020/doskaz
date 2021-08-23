<?php


namespace App\ProfileNotifications;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProfileNotificationRepository
{
    private EntityManagerInterface $entityManager;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(ProfileNotification::class);
    }

    public function add(ProfileNotification $notification)
    {
        $this->entityManager->persist($notification);
    }
}
