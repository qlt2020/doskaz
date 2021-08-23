<?php


namespace App\UserEvents\ObjectSupplemented;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectRepository;
use App\Objects\PhotosAdding\Event\PhotosAddingRequestApproved;
use App\UserEvents\UserEvent;
use App\UserEvents\UserEventRepository;
use Doctrine\DBAL\Connection;

class RememberWhenObjectSupplemented implements EventListener
{
    private Flusher $flusher;

    private UserEventRepository $repository;

    private Connection $connection;

    private MapObjectRepository $mapObjectRepository;

    public function __construct(Flusher $flusher, UserEventRepository $repository, Connection $connection, MapObjectRepository $mapObjectRepository)
    {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->connection = $connection;
        $this->mapObjectRepository = $mapObjectRepository;
    }

    /**
     * @param PhotosAddingRequestApproved $event
     * @throws \Exception
     */
    public function handle($event)
    {
        $object = $this->mapObjectRepository->find($event->objectId);
        if ($object->createdBy()) {
            $this->repository->add(new UserEvent($object->createdBy(), new ObjectSupplementedData($event->createdBy, $object->uuid())));
            $this->flusher->flush();
        }
    }

    public function supports($event): bool
    {
        return $event instanceof PhotosAddingRequestApproved;
    }
}
