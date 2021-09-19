<?php


namespace App\RegionalRepresentatives;

use App\Blog\Image;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class RegionalRepresentativeData implements DataObject
{
    public $id;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $phone;

    /**
     * @var string|int|null
     * @Assert\NotBlank()
     */
    public $cityId;

    /**
     * @Assert\NotBlank()
     * @var Image|null
     */
    public $photo;
}
