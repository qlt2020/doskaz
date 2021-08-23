<?php


namespace App\Tasks\ProfileCompletion;

class ProfileCompletionTaskDone
{
    /**
     * @var integer
     */
    public $userId;

    /**
     * @var int
     */
    public $reward;

    /**
     * ProfileCompletionTaskDone constructor.
     * @param int $userId
     */
    public function __construct(int $userId, int $reward)
    {
        $this->userId = $userId;
        $this->reward = $reward;
    }
}
