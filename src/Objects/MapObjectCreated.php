<?php


namespace App\Objects;

use Ramsey\Uuid\UuidInterface;

class MapObjectCreated
{
    public $id;
    /**
     * @var int|null
     */
    public $createdBy;

    /**
     * MapObjectCreated constructor.
     * @param UuidInterface $id
     * @param int|null $createdBy
     */
    public function __construct(UuidInterface $id, ?int $createdBy = null)
    {
        $this->id = $id;
        $this->createdBy = $createdBy;
    }
}
