<?php


namespace App\Users\Security\Oauth;

use App\Infrastructure\Doctrine\Flusher;
use App\Users\FullName;
use App\Users\User;
use App\Users\UserRepository;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

class OauthService
{
    private ServiceLocator $oauthProviderLocator;
    /**
     * @var OauthCredentialsRepository
     */
    private OauthCredentialsRepository $credentialsRepository;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(ServiceLocator $oauthProviderLocator, OauthCredentialsRepository $credentialsRepository, UserRepository $userRepository, Flusher $flusher)
    {
        $this->oauthProviderLocator = $oauthProviderLocator;
        $this->credentialsRepository = $credentialsRepository;
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function userByProviderAndCode(string $providerKey, string $code, ?string $redirectUri = null)
    {
        if (!$this->oauthProviderLocator->has($providerKey)) {
            throw new ProviderNotFound(sprintf('Provider %s not found', $providerKey));
        }

        /**
         * @var AbstractProvider $provider
         */
        $provider = $this->oauthProviderLocator->get($providerKey);

        try {
            $parameters = [
                'code' => $code,
            ];
            if ($redirectUri) {
                $parameters['redirect_uri'] = $redirectUri;
            }
            $accessToken = $provider->getAccessToken('authorization_code', $parameters);
        } catch (IdentityProviderException $e) {
            throw new InvalidCode($e->getMessage(), $e->getCode(), $e);
        }

        /**
         * @var ResourceOwnerInterface|FacebookUser|GoogleUser|\J4k\OAuth2\Client\Provider\User $resourceOwner
         */
        $resourceOwner = $provider->getResourceOwner($accessToken);
        $credentials = $this->credentialsRepository->findByNetworkAndIdentifier($providerKey, (string)$resourceOwner->getId());
        if ($credentials) {
            return [
                'user' => $this->userRepository->find($credentials->id()),
                'created' => false
            ];
        }
        $name = new FullName($resourceOwner->getFirstName(), $resourceOwner->getLastName());
        $user = new User($name, null);
        $this->userRepository->add($user);
        $credentials = new OauthCredentials($user->id(), $providerKey, (string)$resourceOwner->getId(), $name->firstAndLast());
        $this->credentialsRepository->add($credentials);
        $this->flusher->flush();
        return [
            'user' => $user,
            'created' => true
        ];
    }
}
