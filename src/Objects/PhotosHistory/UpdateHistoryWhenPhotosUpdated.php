<?php


namespace App\Objects\PhotosHistory;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectRepository;
use App\Objects\PhotosUpdated;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UpdateHistoryWhenPhotosUpdated implements EventListener
{
    private $photosHistoryRepository;

    private $mapObjectRepository;

    private $tokenStorage;

    private $flusher;

    public function __construct(PhotosHistoryRepository $photosHistoryRepository, TokenStorageInterface $tokenStorage, MapObjectRepository $mapObjectRepository, Flusher $flusher)
    {
        $this->photosHistoryRepository = $photosHistoryRepository;
        $this->tokenStorage = $tokenStorage;
        $this->mapObjectRepository = $mapObjectRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param PhotosUpdated $event
     */
    public function handle($event)
    {
        $token = $this->tokenStorage->getToken();
        $who = null;
        if (!is_null($token)) {
            $who = $token->getUser()->id();
        }

        $mapObject = $this->mapObjectRepository->findByUuid($event->uuid);

        $photos = $this->photosHistoryRepository->findBy([
            'objectId' => $mapObject->id()
        ]);

        $newPhotos = $event->newPhotos;

        foreach ($photos as $photo) {
            if (!$event->newPhotos->contains($photo->file())) {
                $this->photosHistoryRepository->remove($photo);
            } else {
                $newPhotos->remove($photo->file());
            }
        }

        foreach ($event->newPhotos as $photo) {
            $this->photosHistoryRepository->add(
                new PhotosHistory($mapObject->id(), $photo, $who)
            );
        }

        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof PhotosUpdated;
    }
}
