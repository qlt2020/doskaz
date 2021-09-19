<?php


namespace App\Users;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserProfileData implements DataObject
{
    /**
     * @var string|null
     */
    public $firstName;

    /**
     * @var string|null
     */
    public $lastName;

    /**
     * @var string|null
     */
    public $middleName;

    /**
     * @var string|null
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string|null
     */
    public $phoneChangeToken;

    /**
     * @var string|null
     */
    public ?string $status = null;

    /**
     * @var string|null
     */
    public $gender;

    /**
     * @var string|null
     */
    public $category;

    /**
     * @var integer|null
     */
    public $city_id;

    /**
     * @var string|null
     * @Assert\Date()
     */
    public $birthday;
}
