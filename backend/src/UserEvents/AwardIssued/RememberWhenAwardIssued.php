<?php


namespace App\UserEvents\AwardIssued;

use App\Awards\AwardIssued;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\UserEvents\UserEvent;
use App\UserEvents\UserEventRepository;

class RememberWhenAwardIssued implements EventListener
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
     * @param $event AwardIssued
     * @throws \Exception
     */
    public function handle($event)
    {
        $this->userEventRepository->add(new UserEvent($event->userId, new AwardIssuedData($event->id)));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof AwardIssued;
    }
}
