<?php

namespace App\Objects\EventListener;

use App\Infrastructure\DomainEvents\EventListener;
use App\Notification\Notification;
use App\Notification\ObjectCreatedNotificationData;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Category;
use App\Objects\MapObjectCreated;
use App\Objects\MapObjectRepository;
use App\Users\Subscribe\UserSubscribe;
use App\Users\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class AddNotificationToSubscribers implements EventListener
{
    private MapObjectRepository $mapObjectRepository;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, MapObjectRepository $mapObjectRepository)
    {
        $this->em = $em;
        $this->mapObjectRepository = $mapObjectRepository;
    }

    /**
     * @param MapObjectCreated $event
     */
    public function handle($event)
    {
        $object = $this->mapObjectRepository->findByUuid($event->id);
        $subscribers = $this->em->getRepository(UserSubscribe::class)->findAll();

        if (count($subscribers) > 0) {
            foreach ($subscribers as $subscriber) {
                if (in_array($object->getOverallScope()->{$subscriber->getData()->content->category}, [
                    AccessibilityScore::SCORE_FULL_ACCESSIBLE, AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE,
                ])) {
                    $notification = new Notification($subscriber->getUser(),
                        new ObjectCreatedNotificationData($object->uuid(), 'Новый объект'));
                    $this->em->persist($notification);
                    $this->em->flush();
                }
            }
        }
    }

    public function supports($event): bool
    {
        return $event instanceof MapObjectCreated;
    }
}
