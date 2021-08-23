<?php


namespace App\ProfileNotifications;

class PointsEarnedNotificationData extends ProfileNotificationData
{
    public int $points;

    public string $taskName;

    public string $taskType;

    public const TASK_TYPES = [
        'profile_completion',
        'administration',
        'daily'
    ];

    /**
     * PointsEarnedNotificationData constructor.
     * @param int $points
     * @param string $taskName
     * @param string $taskType
     */
    public function __construct(int $points, string $taskName, string $taskType)
    {
        $this->points = $points;
        $this->taskName = $taskName;
        $this->taskType = $taskType;
    }
}
