<?php


namespace App\Tasks\Daily;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Tasks\DailyVerification\DailyVerificationTaskDone;
use App\Users\UserRegistered;

class IssueDailyTaskWhenUserDailyVerificationTaskDone implements EventListener
{
    /**
     * @var DailyTaskRepository
     */
    private $dailyTaskRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(DailyTaskRepository $dailyTaskRepository, Flusher $flusher)
    {
        $this->dailyTaskRepository = $dailyTaskRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event DailyVerificationTaskDone
     */
    public function handle($event)
    {
        $this->dailyTaskRepository->add(new DailyTask($event->userId));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof DailyVerificationTaskDone;
    }
}
