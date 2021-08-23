<?php


namespace App\UserEvents\LevelReached;

use App\UserEvents\Data;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 */
class LevelReachedData extends Data
{
    /**
     * @var int
     */
    public $level;

    /**
     * @var int
     */
    public $pointsUntilNextLevel;

    /**
     * LevelReached constructor.
     * @param int $level
     * @param int $pointsUntilNextLevel
     */
    public function __construct(int $level, int $pointsUntilNextLevel)
    {
        $this->level = $level;
        $this->pointsUntilNextLevel = $pointsUntilNextLevel;
    }
}
