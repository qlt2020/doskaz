<?php


namespace App\Levels;

class LevelReached
{
    public int $userId;

    public int $level;

    /**
     * LevelReached constructor.
     * @param int $userId
     * @param int $level
     */
    public function __construct(int $userId, int $level)
    {
        $this->userId = $userId;
        $this->level = $level;
    }
}
