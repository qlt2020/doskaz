<?php
declare(strict_types=1);

namespace App\Users\Security\Oauth;

use App\Infrastructure\ObjectResolver\DataObject;

final class OauthData implements DataObject
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $provider;

    /**
     * @var string|null
     */
    public $redirectUri;
}
