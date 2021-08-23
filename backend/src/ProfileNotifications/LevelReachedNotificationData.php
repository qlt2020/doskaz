<?php


namespace App\ProfileNotifications;

class LevelReachedNotificationData extends ProfileNotificationData
{
    public int $level;

    public ?string $unlockedAbility;

    /**
     * LevelReachedNotificationData constructor.
     * @param int $level
     * @param string $unlockedAbility
     */
    public function __construct(int $level, ?string $unlockedAbility = null)
    {
        $this->level = $level;
        $this->unlockedAbility = $unlockedAbility;
    }
}
