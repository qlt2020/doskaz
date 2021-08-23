<?php


namespace App\Tasks\DailyVerification;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\Verification\ObjectVerified;
use App\Tasks\CurrentTaskProvider;

class CompleteDailyVerificationTaskWhenObjectVerified implements EventListener
{
    /**
     * @var DailyVerificationTaskRepository
     */
    private $dailyVerificationTaskRepository;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var CurrentTaskProvider
     */
    private $currentTaskProvider;

    public function __construct(DailyVerificationTaskRepository $dailyVerificationTaskRepository, Flusher $flusher, CurrentTaskProvider $currentTaskProvider)
    {
        $this->dailyVerificationTaskRepository = $dailyVerificationTaskRepository;
        $this->flusher = $flusher;
        $this->currentTaskProvider = $currentTaskProvider;
    }

    /**
     * @param $event ObjectVerified
     */
    public function handle($event)
    {
        $task = $this->dailyVerificationTaskRepository->findCurrentForUser($event->userId);
        if (!$task) {
            return;
        }
        $this->dailyVerificationTaskRepository->forAggregate($task->id(), function (DailyVerificationTask $task) use ($event) {
            $task->complete($event->objectId);
        });
    }

    public function supports($event): bool
    {
        return $event instanceof ObjectVerified
            && !$this->dailyVerificationTaskRepository->hasCompletedForUser($event->objectId, $event->userId)
            && $this->currentTaskProvider->execute($event->userId) instanceof DailyVerificationTask;
    }
}
