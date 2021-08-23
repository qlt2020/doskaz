<?php


namespace App\Users;

class UserAbilities
{
    /**
     * @var bool
     */
    public $avatarUpload;
    /**
     * @var bool
     */
    public $statusChange;

    /**
     * UserAbilities constructor.
     * @param bool $avatarUpload
     * @param bool $statusChange
     */
    public function __construct(bool $avatarUpload, bool $statusChange)
    {
        $this->avatarUpload = $avatarUpload;
        $this->statusChange = $statusChange;
    }
}
