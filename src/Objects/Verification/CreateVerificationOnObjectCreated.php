<?php


namespace App\Objects\Verification;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectCreated;

class CreateVerificationOnObjectCreated implements EventListener
{
    private $repository;

    private $flusher;

    public function __construct(VerificationRepository $repository, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event MapObjectCreated
     */
    public function handle($event)
    {
        $verification = new Verification($event->id);
        $this->repository->add($verification);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof MapObjectCreated;
    }
}
