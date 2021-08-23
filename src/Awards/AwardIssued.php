<?php


namespace App\Awards;

use Ramsey\Uuid\UuidInterface;

class AwardIssued
{
    public UuidInterface $id;

    public int $userId;

    /**
     * AwardIssued constructor.
     * @param UuidInterface $id
     * @param int $userId
     */
    public function __construct(UuidInterface $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }
}
