<?php


namespace App\Objects\EventsHistory\Listener;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\EventsHistory\Event;
use App\Objects\EventsHistory\EventRepository;
use App\Objects\EventsHistory\Type\VerificationConfirmed;
use App\Objects\MapObjectRepository;

class VerificationConfirmedListener implements EventListener
{
    private $flusher;

    private $eventRepository;
    /**
     * @var MapObjectRepository
     */
    private $mapObjectRepository;

    /**
     * ReviewCreatedListener constructor.
     * @param Flusher $flusher
     * @param EventRepository $eventRepository
     * @param MapObjectRepository $mapObjectRepository
     */
    public function __construct(Flusher $flusher, EventRepository $eventRepository, MapObjectRepository $mapObjectRepository)
    {
        $this->flusher = $flusher;
        $this->eventRepository = $eventRepository;
        $this->mapObjectRepository = $mapObjectRepository;
    }

    /**
     * @param \App\Objects\Verification\VerificationConfirmed $event
     */
    public function handle($event)
    {
        $object = $this->mapObjectRepository->findByUuid($event->id);
        $historyEvent = new Event($object->id(), $event->userId, new VerificationConfirmed());
        $this->eventRepository->add($historyEvent);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof \App\Objects\Verification\VerificationConfirmed;
    }
}
