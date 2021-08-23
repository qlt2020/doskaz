<?php


namespace App\Levels;

use App\Infrastructure\DomainEvents\EventListener;
use App\Tasks\Daily\DailyTaskDone;

class IncreaseLevelOnDailyTaskDone implements EventListener
{
    private LevelRepository $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    /**
     * @param $event DailyTaskDone
     */
    public function handle($event)
    {
        $this->levelRepository->forAggregate($event->userId, function (Level $level) use ($event) {
            $level->addPoints($event->reward);
        });
    }

    public function supports($event): bool
    {
        return $event instanceof DailyTaskDone;
    }
}
