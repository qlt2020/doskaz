<?php


namespace App\Objects\Verification;

use Ramsey\Uuid\UuidInterface;

class VerificationRejected
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
     * VerificationRejected constructor.
     * @param UuidInterface $id
     * @param int $userId
     */
    public function __construct(UuidInterface $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }
}
