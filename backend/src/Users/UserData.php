<?php
declare(strict_types=1);

namespace App\Users;

use App\Infrastructure\ObjectResolver\DataObject;

final class UserData implements DataObject
{
    public $id;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var array
     */
    public $roles = [];
}
