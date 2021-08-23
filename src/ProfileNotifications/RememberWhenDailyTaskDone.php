<?php


namespace App\ProfileNotifications;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Tasks\ProfileCompletion\ProfileCompletionTaskDone;

class RememberWhenDailyTaskDone implements EventListener
{
    /**
     * @var Flusher
     */
    private Flusher $flusher;
    /**
     * @var ProfileNotificationRepository
     */
    private ProfileNotificationRepository $notificationRepository;

    public function __construct(Flusher $flusher, ProfileNotificationRepository $notificationRepository)
    {
        $this->flusher = $flusher;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param $event ProfileCompletionTaskDone
     */
    public function handle($event)
    {
        $this->notificationRepository->add(new ProfileNotification($event->userId, new PointsEarnedNotificationData(
            $event->reward,
            'Заполнить профиль',
            'profile_completion'
        )));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof ProfileCompletionTaskDone;
    }
}
