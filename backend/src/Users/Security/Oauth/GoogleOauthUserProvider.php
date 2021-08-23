<?php


namespace App\Users\Security\Oauth;

use App\Users\User;
use App\Users\UserRepository;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;

final class GoogleOauthUserProvider
{
    private $provider;

    private $credentialsRepository;

    private $userRepository;

    public function __construct(Google $google, OauthCredentialsRepository $credentialsRepository, UserRepository $userRepository)
    {
        $this->provider = $google;
        $this->credentialsRepository = $credentialsRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->provider->getAuthorizationUrl();
    }

    /**
     * @param string $code
     * @return User
     * @throws IdentityProviderException
     */
    public function getUser(string $code): User
    {
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        /**
         * @var GoogleUser $resourceOwner
         */
        $resourceOwner = $this->provider->getResourceOwner($accessToken);

        $credentials = $this->credentialsRepository->findByNetworkAndIdentifier('google', (string)$resourceOwner->getId());
        if ($credentials) {
            return $this->userRepository->find($credentials->id());
        }

        $credentials = new OauthCredentials('google', (string)$resourceOwner->getId());
        $this->credentialsRepository->add($credentials);

        $user = new User($resourceOwner->getName(), $resourceOwner->getEmail());
    }
}
