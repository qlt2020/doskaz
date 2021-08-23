<?php


namespace App\UserEvents\ObjectAdded;

use App\UserEvents\Data;
use Ramsey\Uuid\UuidInterface;

class ObjectAddedData extends Data
{
    public UuidInterface $objectId;

    /**
     * ObjectAddedData constructor.
     * @param UuidInterface $objectId
     */
    public function __construct(UuidInterface $objectId)
    {
        $this->objectId = $objectId;
    }
}
