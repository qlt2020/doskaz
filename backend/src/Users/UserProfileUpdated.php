<?php


namespace App\Users;

class UserProfileUpdated
{
    public $userId;

    public function __construct(int $id)
    {
        $this->userId = $id;
    }
}
