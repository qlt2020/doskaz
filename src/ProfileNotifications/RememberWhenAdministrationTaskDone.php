<?php


namespace App\ProfileNotifications;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Tasks\Administration\AdministrationTaskDone;
use App\Tasks\Daily\DailyTaskDone;
use Doctrine\DBAL\Connection;

class RememberWhenAdministrationTaskDone implements EventListener
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
     * @param $event AdministrationTaskDone
     */
    public function handle($event)
    {
        $this->notificationRepository->add(new ProfileNotification($event->userId, new PointsEarnedNotificationData(
            $event->reward,
            'Добавить 1 объект',
            'daily'
        )));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof DailyTaskDone;
    }
}
