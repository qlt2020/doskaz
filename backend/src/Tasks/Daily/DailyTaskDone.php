<?php


namespace App\Tasks\Daily;

use Ramsey\Uuid\UuidInterface;

class DailyTaskDone
{
    /**
     * @var UuidInterface
     */
    public $id;
    /**
     * @var int
     */
    public $userId;
    /**
     * @var int
     */
    public $reward;

    /**
     * DailyTaskDone constructor.
     * @param UuidInterface $id
     * @param int $userId
     * @param int $reward
     */
    public function __construct(UuidInterface $id, int $userId, int $reward)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->reward = $reward;
    }
}
