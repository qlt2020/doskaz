<?php


namespace App\Users\Security;

use App\Infrastructure\Doctrine\Flusher;
use App\Users\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tuupola\Base62;

final class UserAuthenticator
{
    private $accessTokenRepository;

    private $flusher;

    public function __construct(AccessTokenRepository $accessTokenRepository, Flusher $flusher)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function authenticate(Request $request, User $user): JsonResponse
    {
        $accessToken = new AccessToken($user->id());
        $this->accessTokenRepository->add($accessToken);
        $this->flusher->flush();

        $response = new JsonResponse([
            'token' => $accessToken->value()
        ], 204);

        $accessTokenCookie = new Cookie(
            CookieAccessTokenAuthenticator::COOKIE_NAME,
            $accessToken->value(),
            $accessToken->expiresAt(),
            '/',
            null,
            $request->isSecure(),
            true,
            false
        );

        $response->headers->setCookie($accessTokenCookie);
        return $response;
    }
}
