<?php


namespace App\UserEvents;

class Context
{
    public int $userId;

    /**
     * Context constructor.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
