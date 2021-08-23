<?php


namespace App\Tasks\Daily;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectCreated;
use App\Tasks\Administration\TaskForObjectFinder;
use App\Tasks\Administration\UserAdministrationTaskRepository;
use App\Tasks\CurrentTaskProvider;

class CompleteDailyTaskOnObjectCreated implements EventListener
{
    private DailyTaskRepository $dailyTaskRepository;

    private CurrentTaskProvider $currentTaskProvider;

    private TaskForObjectFinder $taskForObjectFinder;

    private UserAdministrationTaskRepository $userAdministrationTaskRepository;

    private Flusher $flusher;

    public function __construct(DailyTaskRepository $dailyTaskRepository, CurrentTaskProvider $currentTaskProvider, TaskForObjectFinder $taskForObjectFinder, UserAdministrationTaskRepository $userAdministrationTaskRepository, Flusher $flusher)
    {
        $this->dailyTaskRepository = $dailyTaskRepository;
        $this->currentTaskProvider = $currentTaskProvider;
        $this->taskForObjectFinder = $taskForObjectFinder;
        $this->userAdministrationTaskRepository = $userAdministrationTaskRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event MapObjectCreated
     */
    public function handle($event)
    {
        $administrationTask = $this->taskForObjectFinder->find($event->id);
        if ($administrationTask) {
            $completedTask = $administrationTask->completeForUser($event->createdBy, $event->id);
            $this->userAdministrationTaskRepository->add($completedTask);
            $this->flusher->flush();
            return;
        }

        $currentTask = $this->currentTaskProvider->execute($event->createdBy);
        if (is_null($administrationTask) && $currentTask instanceof DailyTask) {
            $this->dailyTaskRepository->forAggregate($currentTask->id(), function (DailyTask $task) use ($event) {
                $task->objectAdded($event->id);
            });
        }
    }

    public function supports($event): bool
    {
        return $event instanceof MapObjectCreated && !is_null($event->createdBy);
    }
}
