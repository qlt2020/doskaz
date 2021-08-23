<?php


namespace App\UserAbilities;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Levels\LevelReached;

class GiveAbilityOnLevelReached implements EventListener
{
    /**
     * @var UnlockedAbilityRepository
     */
    private UnlockedAbilityRepository $repository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(UnlockedAbilityRepository $repository, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    /**
     * @param LevelReached $event
     */
    public function handle($event)
    {
        $this->repository->add(new UnlockedAbility($event->userId, $event->level, UnlockedAbility::ABILITIES_MAP[$event->level]));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof LevelReached && array_key_exists($event->level, UnlockedAbility::ABILITIES_MAP);
    }
}
