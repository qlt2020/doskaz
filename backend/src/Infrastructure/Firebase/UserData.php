<?php


namespace App\Infrastructure\Firebase;

class UserData
{
    /**
     * @var string|null
     */
    public $phoneNumber;

    public function __construct(?string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
}
