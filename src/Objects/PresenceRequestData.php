<?php


namespace App\Objects;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class PresenceRequestData implements DataObject
{
    /**
     * @Assert\NotBlank()
     */
    public ?string $name;

    /**
     * @Assert\NotBlank
     */
    public ?string $otherNames;
}
