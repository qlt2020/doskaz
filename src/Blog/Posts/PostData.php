<?php
declare(strict_types=1);

namespace App\Blog\Posts;

use App\Blog\Image;
use App\Blog\MetaData;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

final class PostData implements DataObject
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @var string|null
     */
    public $annotation;

    /**
     * @var string|null
     */
    public $content;

    /**
     * @var string|null
     */
    public $slug;

    /**
     * @var \DateTimeImmutable|null
     * @Assert\NotBlank()
     */
    public $publishedAt;

    /**
     * @var boolean
     */
    public $isPublished;

    /**
     * @var integer|null|string
     * @Assert\NotBlank()
     */
    public $categoryId;

    /**
     * @var MetaData|null
     */
    public $meta;

    /**
     * @var Image|null
     * @Assert\NotBlank()
     */
    public $image;
}
