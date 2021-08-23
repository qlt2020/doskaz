<?php


namespace App\Objects\EventsHistory\Listener;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\EventsHistory\Event;
use App\Objects\EventsHistory\EventRepository;
use App\Objects\Reviews\Event\ReviewCreated;

class ReviewCreatedListener implements EventListener
{
    private $flusher;

    private $eventRepository;

    /**
     * ReviewCreatedListener constructor.
     * @param $flusher
     * @param $eventRepository
     */
    public function __construct(Flusher $flusher, EventRepository $eventRepository)
    {
        $this->flusher = $flusher;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param ReviewCreated $event
     */
    public function handle($event)
    {
        $historyEvent = new Event($event->objectId, $event->userId, new \App\Objects\EventsHistory\Type\ReviewCreated($event->id));
        $this->eventRepository->add($historyEvent);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof ReviewCreated;
    }
}
