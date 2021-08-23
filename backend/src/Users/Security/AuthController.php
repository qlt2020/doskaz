<?php
declare(strict_types=1);

namespace App\Users\Security;

use App\Infrastructure\Doctrine\Flusher;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
final class AuthController extends AbstractController
{
    /**
     * @Route(path="/api/token", methods={"DELETE"})
     * @param Request $request
     * @param AccessTokenRepository $accessTokenRepository
     * @param Flusher $flusher
     */
    public function logout(Request $request, AccessTokenRepository $accessTokenRepository, Flusher $flusher)
    {
        $token = $accessTokenRepository->find($request->cookies->get(CookieAccessTokenAuthenticator::COOKIE_NAME));
        if (!$token) {
            return;
        }
        $accessTokenRepository->remove($token);
        $flusher->flush();
    }

    /**
     * @Route(path="/api/accessToken", methods={"DELETE"})
     * @Delete(
     *     path="/api/accessToken",
     *     security={{"clientAuth": {}}},
     *     tags={"Токены"},
     *     summary="Удаление токена доступа",
     *     @Response(response="204", description="")
     * )
     * @param Request $request
     * @param AccessTokenRepository $accessTokenRepository
     * @param Flusher $flusher
     */
    public function accessTokenLogout(Request $request, AccessTokenRepository $accessTokenRepository, Flusher $flusher)
    {
        $token = substr($request->headers->get('Authorization', ''), 7);
        $token = $accessTokenRepository->find($token);
        if (!$token) {
            return;
        }
        $accessTokenRepository->remove($token);
        $flusher->flush();
    }
}
