<?php


namespace App\Users\Security;

use App\Infrastructure\Doctrine\Flusher;
use App\Users\FullName;
use App\Users\Security\Oauth\OauthCredentials;
use App\Users\Security\Oauth\OauthCredentialsRepository;
use App\Users\User;
use App\Users\UserRepository;
use Jose\Component\Checker\AudienceChecker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\InvalidClaimException;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\IssuerChecker;
use Jose\Component\Checker\MissingMandatoryClaimException;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWKSet;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Property;

/**
 * @Route(path="/api/accessToken")
 */
class SignInWithFacebookController extends AbstractController
{
    /**
     * @Route(path="/facebook", methods={"POST"})
     * @param Request $request
     * @param OauthCredentialsRepository $credentialsRepository
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     * @param UserAuthenticator $authenticator
     * @return JsonResponse|Response
     * @throws InvalidClaimException
     * @throws MissingMandatoryClaimException
     * @Post(
     *     path="/api/accessToken/facebook",
     *     summary="Получение токена доступа через facebook",
     *     tags={"Токены"},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(type="string", property="token")
     *         )
     *     ),
     *     @\OpenApi\Annotations\Response(response="201", description="Аутентифицирован новый пользователь", @JsonContent(@Property(type="string", property="token"))),
     *     @\OpenApi\Annotations\Response(response="200", description="Аутентифицирован существующий пользователь", @JsonContent(@Property(type="string", property="token"))),
     * )
     */
    public function byToken(Request $request, OauthCredentialsRepository $credentialsRepository, UserRepository $userRepository, Flusher $flusher, UserAuthenticator $authenticator)
    {
        $requestData = json_decode($request->getContent(), true);

        $token = $requestData['token'];

        $response = file_get_contents("https://graph.facebook.com/v8.0/me?access_token=$token");

        $profile = json_decode($response, true);

        $credential = $credentialsRepository->findByNetworkAndIdentifier('facebook', $profile['id']);
        $created = false;

        if (!$credential) {
            $user = new User(FullName::parseFromString($profile['name']));
            $userRepository->add($user);
            $credential = new OauthCredentials($user->id(), 'facebook', $profile['id']);
            $credentialsRepository->add($credential);
            $flusher->flush();
            $created = true;
        }

        return $authenticator->authenticate($request, $userRepository->find($credential->id()))->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
    }
}
