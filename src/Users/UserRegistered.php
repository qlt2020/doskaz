<?php


namespace App\Users;

class UserRegistered
{
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
