<?php


namespace App\Tasks;

class CurrentTaskData
{
    /**
     * @var int
     */
    public $progress;

    /**
     * @var string
     */
    public $title;

    public int $pointsReward;

    /**
     * CurrentTaskData constructor.
     * @param int $progress
     * @param string $title
     * @param int $pointsReward
     */
    public function __construct(int $progress, string $title, int $pointsReward)
    {
        $this->progress = $progress;
        $this->title = $title;
        $this->pointsReward = $pointsReward;
    }
}
