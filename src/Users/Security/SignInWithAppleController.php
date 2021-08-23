<?php


namespace App\Users\Security;

use App\Infrastructure\Doctrine\Flusher;
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
class SignInWithAppleController extends AbstractController
{
    /**
     * @Route(path="/apple", methods={"POST"})
     * @param Request $request
     * @param OauthCredentialsRepository $credentialsRepository
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     * @param UserAuthenticator $authenticator
     * @return JsonResponse|Response
     * @throws InvalidClaimException
     * @throws MissingMandatoryClaimException
     * @Post(
     *     path="/api/accessToken/apple",
     *     summary="Получение токена доступа через apple",
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

        $jwkString = file_get_contents('https://appleid.apple.com/auth/keys');
        $set = JWKSet::createFromJson($jwkString);

        $serializerManager = new JWSSerializerManager([
            new CompactSerializer()
        ]);

        $token = $serializerManager->unserialize($requestData['token']);

        $x = new AlgorithmManager([new RS256()]);

        $verifier = new JWSVerifier($x);
        $verified = $verifier->verifyWithKeySet($token, $set, 0);

        if (!$verified) {
            return $this->json(['message' => 'Invalid token'], 400);
        }

        $checker = new ClaimCheckerManager([
            new IssuedAtChecker(),
            new NotBeforeChecker(),
            new IssuerChecker(['https://appleid.apple.com']),
            new ExpirationTimeChecker(),
            new AudienceChecker('kz.zed.DosKaz')
        ]);

        $claims = json_decode($token->getPayload(), true);
        $checker->check($claims);
        $credential = $credentialsRepository->findByNetworkAndIdentifier('apple', $claims['sub']);
        $created = false;

        if (!$credential) {
            $user = new User(null);
            $userRepository->add($user);
            $credential = new OauthCredentials($user->id(), 'apple', $claims['sub']);
            $credentialsRepository->add($credential);
            $flusher->flush();
            $created = true;
        }

        return $authenticator->authenticate($request, $userRepository->find($credential->id()))->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
    }
}
