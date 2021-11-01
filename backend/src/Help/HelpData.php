<?php

namespace App\Help;

use App\Blog\Image;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class HelpData implements DataObject
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $title;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $title_kz;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $title_en;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     */
    public $description;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     */
    public $description_kz;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     */
    public $description_en;

    /**
     * @var Image|null
     * @Assert\NotBlank()
     */
    public $image;

    /**
     * @var Image|null
     * @Assert\NotBlank()
     */
    public $image_kz;

    /**
     * @var Image|null
     * @Assert\NotBlank()
     */
    public $image_en;

    /**
     * @Assert\NotBlank
     */
    public $category;
}