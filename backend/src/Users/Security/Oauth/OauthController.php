<?php


namespace App\Users\Security\Oauth;

use App\Infrastructure\Doctrine\Flusher;
use App\Users\FullName;
use App\Users\Security\UserAuthenticator;
use App\Users\User;
use App\Users\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class OauthController extends AbstractController
{
    private ServiceLocator $oauthProviderLocator;

    public function __construct(ServiceLocator $oauthProviderLocator)
    {
        $this->oauthProviderLocator = $oauthProviderLocator;
    }

    /**
     * @Route(path="/api/oauth/{providerKey}/redirect", methods={"GET"})
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function oauthRedirect(string $providerKey)
    {
        if (!$this->oauthProviderLocator->has($providerKey)) {
            throw new NotFoundHttpException(sprintf('Provider %s not found', $providerKey));
        }
        return new RedirectResponse($this->oauthProviderLocator->get($providerKey)->getAuthorizationUrl());
    }

    /**
     * @param OauthService $oauthService
     * @param Request $request
     * @param OauthData $oauthData
     * @param UserAuthenticator $authenticator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Exception
     * @Route(path="/api/token/oauth", methods={"POST"})
     */
    public function oauthAuthenticate(OauthService $oauthService, Request $request, OauthData $oauthData, UserAuthenticator $authenticator)
    {
        try {
            ['user' => $user, 'created' => $created] = $oauthService->userByProviderAndCode($oauthData->provider, $oauthData->code);
            return $authenticator->authenticate($request, $user)->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch (ProviderNotFound $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        } catch (InvalidCode $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @Route(path="/api/accessToken/oauth", methods={"POST"})
     * @param OauthService $oauthService
     * @param Request $request
     * @param OauthData $oauthData
     * @param UserAuthenticator $authenticator
     * @return Response
     * @throws \Exception
     * @Post(
     *     path="/api/accessToken/oauth",
     *     summary="Получение токена доступа на основе oauth кода",
     *     tags={"Токены"},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(type="string", property="provider", enum={"google", "facebook", "vkontakte", "mailru"}),
     *             @Property(type="string", property="code"),
     *             @Property(type="string", property="redirectUri", nullable=true),
     *         )
     *     ),
     *     @\OpenApi\Annotations\Response(response="404", description="Provider not found"),
     *     @\OpenApi\Annotations\Response(response="400", description="Invalid code"),
     *     @\OpenApi\Annotations\Response(response="201", description="Аутентифицирован новый пользователь", @JsonContent(@Property(type="string", property="token"))),
     *     @\OpenApi\Annotations\Response(response="200", description="Аутентифицирован существующий пользователь", @JsonContent(@Property(type="string", property="token"))),
     * )
     */
    public function oauthAccessTokenAuthenticate(OauthService $oauthService, Request $request, OauthData $oauthData, UserAuthenticator $authenticator, EntityManagerInterface $em)
    {
        $em->getConnection()->beginTransaction();
        try {
            ['user' => $user, 'created' => $created] = $oauthService->userByProviderAndCode($oauthData->provider, $oauthData->code, $oauthData->redirectUri);
            $em->getConnection()->commit();
            return $authenticator->authenticate($request, $user)->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch (ProviderNotFound $exception) {
            $em->getConnection()->rollBack();
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        } catch (InvalidCode $exception) {
            $em->getConnection()->rollBack();
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
    }
}
