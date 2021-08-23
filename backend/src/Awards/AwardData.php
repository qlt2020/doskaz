<?php


namespace App\Awards;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class AwardData implements DataObject
{
    /**
     * @Assert\NotBlank()
     */
    public string $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice(choices=App\Awards\Award::TYPES)
     */
    public string $type;
}
