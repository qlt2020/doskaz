<?php


namespace App\Objects\Verification;

use Ramsey\Uuid\UuidInterface;

class ObjectVerified
{
    /**
     * @var UuidInterface
     */
    public $objectId;
    /**
     * @var int
     */
    public $userId;

    public function __construct(UuidInterface $objectId, int $userId)
    {
        $this->objectId = $objectId;
        $this->userId = $userId;
    }
}
