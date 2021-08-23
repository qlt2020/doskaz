<?php


namespace App\Objects\Reviews;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewData implements DataObject
{
    /**
     * @Assert\NotBlank()
     */
    public $text;
}
