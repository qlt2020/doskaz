<?php


namespace App\Objects\EventListener;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectRepository;
use App\Objects\PhotosAdding\Event\PhotosAddingRequestApproved;

class AddPhotosToObjectWhenRequestApproved implements EventListener
{
    private MapObjectRepository $mapObjectRepository;

    private Flusher $flusher;

    public function __construct(MapObjectRepository $mapObjectRepository, Flusher $flusher)
    {
        $this->mapObjectRepository = $mapObjectRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param PhotosAddingRequestApproved $event
     */
    public function handle($event)
    {
        $object = $this->mapObjectRepository->find($event->objectId);
        $object->addPhotos($event->photos);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof PhotosAddingRequestApproved;
    }
}
