<?php


namespace App\Tasks\DailyVerification;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Tasks\Daily\DailyTaskDone;

class IssueDailyVerificationTaskWhenDailyTaskCompleted implements EventListener
{
    /**
     * @var DailyVerificationTaskRepository
     */
    private $dailyVerificationTaskRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(DailyVerificationTaskRepository $dailyVerificationTaskRepository, Flusher $flusher)
    {
        $this->dailyVerificationTaskRepository = $dailyVerificationTaskRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event DailyTaskDone
     */
    public function handle($event)
    {
        $this->dailyVerificationTaskRepository->add(new DailyVerificationTask($event->userId));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof DailyTaskDone;
    }
}
