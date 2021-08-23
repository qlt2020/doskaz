<?php
declare(strict_types=1);

namespace App\Blog;

use App\Infrastructure\ObjectResolver\DataObject;

final class MetaData implements DataObject
{
    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string|null
     */
    public $keywords;

    /**
     * @var string|null
     */
    public $ogTitle;

    /**
     * @var string|null
     */
    public $ogDescription;

    /**
     * @var Image|null
     */
    public $ogImage;

    public static function fromMeta(?Meta $meta)
    {
        $self = new self();
        if ($meta) {
            $self->title = $meta->title;
            $self->description = $meta->description;
            $self->keywords = $meta->keywords;
            $self->ogTitle = $meta->ogTitle;
            $self->ogDescription = $meta->ogDescription;
            $self->ogImage = $meta->ogImage;
        }
        return $self;
    }
}
