<?php


namespace App\Blog\Comments;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Список комментариев", schema="CommentsList")
 */
class CommentsListData
{
    /**
     * @var int
     * @Property(description="Общее количество комментариев")
     */
    public $count = 0;

    /**
     * @var array
     * @Property(type="array", @Items(ref="#/components/schemas/Comment"))
     */
    public $items = [

    ];

    public function __construct(int $count, array $items)
    {
        $this->count = $count;
        $this->items = $items;
    }
}
