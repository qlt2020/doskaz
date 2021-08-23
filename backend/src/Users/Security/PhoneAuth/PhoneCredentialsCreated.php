<?php


namespace App\Users\Security\PhoneAuth;

class PhoneCredentialsCreated
{
    /**
     * @var int
     */
    public $userId;

    /**
     * PhoneCredentialsCreated constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->userId = $id;
    }
}
