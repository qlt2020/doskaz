<?php
declare(strict_types=1);

namespace App\Blog\Categories;

use App\Blog\MetaData;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

final class CategoryData implements DataObject
{
    /**
     * @var integer|null
     */
    public $id;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $title_kz;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $title_en;

    /**
     * @var string|null
     */
    public $slug;

    /**
     * @var MetaData|null
     */
    public $meta;
}
