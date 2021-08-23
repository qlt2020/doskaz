<?php

namespace App\Infrastructure\DomainEvents;

use Doctrine\ORM\Event\PostFlushEventArgs;

class PostFlushListener
{
    private $eventDispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $events = [];

        foreach ($args->getEntityManager()->getUnitOfWork()->getIdentityMap() as $className => $entities) {
            foreach ($entities as $entity) {
                if ($entity instanceof EventProducer) {
                    foreach ($entity->releaseEvents() as $event) {
                        $events[] = $event;
                    }
                }
            }
        }

        $this->eventDispatcher->dispatch($events);
    }
}
