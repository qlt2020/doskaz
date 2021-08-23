<?php


namespace App\UserEvents\ObjectAdded;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectCreated;
use App\UserEvents\UserEvent;
use App\UserEvents\UserEventRepository;
use Exception;

class RememberWhenObjectAdded implements EventListener
{
    /**
     * @var Flusher
     */
    private Flusher $flusher;
    /**
     * @var UserEventRepository
     */
    private UserEventRepository $userEventRepository;

    public function __construct(Flusher $flusher, UserEventRepository $userEventRepository)
    {
        $this->flusher = $flusher;
        $this->userEventRepository = $userEventRepository;
    }

    /**
     * @param MapObjectCreated $event
     * @throws Exception
     */
    public function handle($event)
    {
        $this->userEventRepository->add(new UserEvent($event->createdBy, new ObjectAddedData($event->id)));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof MapObjectCreated && !empty($event->createdBy);
    }
}
