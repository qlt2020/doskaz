<?php


namespace App\UserEvents\ObjectSupplemented;

use App\UserEvents\Data;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ODM()
 */
class ObjectSupplementedData extends Data
{
    public int $userId;

    public UuidInterface $objectId;

    /**
     * ObjectSupplementedData constructor.
     * @param $userId
     * @param $objectId
     */
    public function __construct(int $userId, UuidInterface $objectId)
    {
        $this->userId = $userId;
        $this->objectId = $objectId;
    }


}