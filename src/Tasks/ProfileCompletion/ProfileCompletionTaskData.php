<?php


namespace App\Tasks\ProfileCompletion;

class ProfileCompletionTaskData
{
    private $firstName;

    private $lastName;

    private $middleName;

    /**
     * ProfileCompletionTaskData constructor.
     * @param $firstName
     * @param $lastName
     * @param $middleName
     */
    public function __construct($firstName, $lastName, $middleName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'middleName' => $this->middleName
        ];
    }
}
