<?php


namespace App\UserEvents\LevelReached;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Levels\LevelReached;
use App\Levels\LevelRepository;
use App\UserEvents\UserEvent;
use App\UserEvents\UserEventRepository;

class RememberWhenLevelReached implements EventListener
{
    /**
     * @var UserEventRepository
     */
    private $userEventRepository;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var LevelRepository
     */
    private $levelRepository;

    public function __construct(UserEventRepository $userEventRepository, Flusher $flusher, LevelRepository $levelRepository)
    {
        $this->userEventRepository = $userEventRepository;
        $this->flusher = $flusher;
        $this->levelRepository = $levelRepository;
    }

    /**
     * @param $event LevelReached
     * @throws \Exception
     */
    public function handle($event)
    {
        $level = $this->levelRepository->find($event->userId);
        $this->userEventRepository->add(new UserEvent($event->userId, new LevelReachedData($event->level, $level->pointsToNextLevel())));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof LevelReached;
    }
}
