<?php


namespace App\Objects\EventsHistory\Listener;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\EventsHistory\Event;
use App\Objects\EventsHistory\EventRepository;
use App\Objects\EventsHistory\Type\Supplemented;
use App\Objects\PhotosAdding\Event\PhotosAddingRequestApproved;

class SupplementedListener implements EventListener
{
    private Flusher $flusher;

    private EventRepository $eventRepository;

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
     * @param PhotosAddingRequestApproved $event
     */
    public function handle($event)
    {
        $historyEvent = new Event($event->objectId, $event->createdBy, new Supplemented());
        $this->eventRepository->add($historyEvent);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof PhotosAddingRequestApproved;
    }
}
