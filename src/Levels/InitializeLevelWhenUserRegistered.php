<?php


namespace App\Levels;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Users\UserRegistered;

class InitializeLevelWhenUserRegistered implements EventListener
{
    private LevelRepository $levelRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(LevelRepository $levelRepository, Flusher $flusher)
    {
        $this->levelRepository = $levelRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event UserRegistered
     */
    public function handle($event)
    {
        $level = new Level($event->id);
        $this->levelRepository->add($level);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof UserRegistered;
    }
}
