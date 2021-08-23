<?php


namespace App\Tasks\Administration;

use Ramsey\Uuid\UuidInterface;

class AdministrationTaskDone
{
    public UuidInterface $id;

    public int $userId;

    public int $reward;

    public function __construct(UuidInterface $id, int $userId, int $reward)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->reward = $reward;
    }
}
