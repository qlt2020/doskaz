<?php


namespace App\Objects\Reviews\Event;

class ReviewCreated
{
    public $id;

    public $objectId;

    public $userId;

    /**
     * ReviewCreated constructor.
     * @param $id
     * @param $objectId
     * @param $userId
     */
    public function __construct($id, $objectId, $userId)
    {
        $this->id = $id;
        $this->objectId = $objectId;
        $this->userId = $userId;
    }
}
