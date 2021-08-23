<?php


namespace App\ProfileNotifications;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Levels\LevelReached;
use App\UserAbilities\UnlockedAbility;

class RememberWhenLevelReached implements EventListener
{
    /**
     * @var ProfileNotificationRepository
     */
    private ProfileNotificationRepository $notificationRepository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(ProfileNotificationRepository $notificationRepository, Flusher $flusher)
    {
        $this->notificationRepository = $notificationRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event LevelReached
     */
    public function handle($event)
    {
        $profileNotification = new ProfileNotification(
            $event->userId,
            new LevelReachedNotificationData($event->level, UnlockedAbility::ABILITIES_MAP[$event->level] ?? null)
        );
        $this->notificationRepository->add($profileNotification);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof LevelReached;
    }
}
