<?php

namespace App\Help\HelpCategory;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class HelpCategoryData implements DataObject
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $name_kz;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     */
    public $name_en;
}