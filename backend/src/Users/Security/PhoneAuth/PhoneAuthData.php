<?php
declare(strict_types=1);

namespace App\Users\Security\PhoneAuth;

use App\Infrastructure\ObjectResolver\DataObject;

final class PhoneAuthData implements DataObject
{
    /**
     * @var string
     */
    public $idToken;
}
