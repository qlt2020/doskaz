<?php


namespace App\Blog\Comments;

use Ramsey\Uuid\UuidInterface;

class PopularityIncreased
{
    /**
     * @var UuidInterface
     */
    public $id;

    /**
     * PopularityIncreased constructor.
     * @param UuidInterface $id
     */
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }
}
