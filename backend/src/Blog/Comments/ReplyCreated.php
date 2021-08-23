<?php


namespace App\Blog\Comments;

use Ramsey\Uuid\UuidInterface;

class ReplyCreated
{
    /**
     * @var UuidInterface
     */
    public $id;

    /**
     * @var UuidInterface
     */
    public $parentId;

    /**
     * @var int
     */
    public $userId;

    /**
     * ReplyCreated constructor.
     * @param UuidInterface $id
     * @param UuidInterface $parentId
     * @param int $userId
     */
    public function __construct(UuidInterface $id, UuidInterface $parentId, int $userId)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->userId = $userId;
    }
}
