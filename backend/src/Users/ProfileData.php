<?php


namespace App\Users;

use App\Infrastructure\ObjectResolver\DataObject;
use App\Tasks\CurrentTaskData;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileData
{
    public $id;

    /**
     * @var string|null
     */
    public $email;

    public $phone;

    public $avatar;

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

    public $currentTask;

    public $level;

    public $stats;

    /**
     * @var array
     */
    public $abilities;

    public ?string $status;

    public $gender;

    public $category;

    public $city_id;

    public $birthday;

    /**
     * ProfileData constructor.
     * @param $email
     * @param $phone
     * @param $avatar
     * @param $firstName
     * @param $lastName
     * @param $middleName
     * @param CurrentTaskData|null $currentTask
     * @param $level
     * @param $stats
     * @param array $abilities
     * @param string|null $status
     * @param $gender
     * @param $category
     * @param $city_id
     * @param $birthday
     */
    public function __construct($id, $email, $phone, $avatar, $firstName, $lastName, $middleName, 
                                ?CurrentTaskData $currentTask, $level, $stats, array $abilities, ?string $status,
                                $gender, $category, $city_id, $birthday)
    {
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->avatar = $avatar;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->currentTask = $currentTask;
        $this->level = $level;
        $this->stats = $stats;
        $this->abilities = $abilities;
        $this->status = $status;
        $this->gender = $gender;
        $this->category = $category;
        $this->city_id = $city_id;
        $this->birthday = $birthday;
    }
}
