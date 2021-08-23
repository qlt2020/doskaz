<?php


namespace App\Users\Security;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class CookieAccessTokenAuthenticator extends AbstractGuardAuthenticator
{
    public const COOKIE_NAME = 'ACCESS_TOKEN';

    private $accessTokenRepository;
    private $connection;
    private $flusher;

    public function __construct(AccessTokenRepository $accessTokenRepository, Connection $connection, Flusher $flusher)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->connection = $connection;
        $this->flusher = $flusher;
    }

    public function supports(Request $request)
    {
        if (!$request->cookies->has(self::COOKIE_NAME)) {
            return false;
        }
        if (!$this->accessTokenRepository->find($request->cookies->get(self::COOKIE_NAME))) {
            return false;
        }
        return true;
    }

    public function getCredentials(Request $request)
    {
        $cookie = $request->cookies->get(self::COOKIE_NAME);
        return $this->accessTokenRepository->find($cookie);
    }

    /**
     * @param AccessToken $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $userData = $this->connection->createQueryBuilder()
            ->select('id, name, roles')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $credentials->userId())
            ->execute()
            ->fetch();

        return new AuthenticatedUser($userData['id'], $userData['name'], $this->connection->convertToPHPValue($userData['roles'], 'json_array'));
    }

    /**
     * @param AccessToken $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$credentials->isExpired()) {
            return true;
        }

        $this->accessTokenRepository->remove($credentials);
        $this->flusher->flush();
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new Response('', 401);
        if ($request->cookies->has(self::COOKIE_NAME)) {
            $response->headers->clearCookie(self::COOKIE_NAME);
        }
        return $response;
    }
}
