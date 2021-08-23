<?php


namespace App\Users\Security\PhoneAuth;

use App\Infrastructure\Doctrine\Flusher;
use App\Users\Security\UserAuthenticator;
use App\Users\User;
use App\Users\UserRepository;
use GuzzleHttp\Client;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthController
 * @package App\Users\Security\PhoneAuth
 */
class AuthController extends AbstractController
{
    private $firebaseApiKey;

    public function __construct($firebaseApiKey)
    {
        $this->firebaseApiKey = $firebaseApiKey;
    }

    /**
     * @Route(path="/api/token/phone", methods={"POST"})
     * @param Request $request
     * @param PhoneAuthData $authData
     * @param CredentialsRepository $credentialsRepository
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     * @param UserAuthenticator $authenticator
     * @return Response|null
     * @throws \Exception
     */
    public function index(Request $request, PhoneAuthData $authData, CredentialsRepository $credentialsRepository, UserRepository $userRepository, Flusher $flusher, UserAuthenticator $authenticator)
    {
        $client = new Client([
            'query' => [
                'key' => $this->firebaseApiKey
            ]
        ]);
        $data = null;

        $data = $client->post('https://www.googleapis.com/identitytoolkit/v3/relyingparty/getAccountInfo', [
            'json' => ['idToken' => $authData->idToken]
        ]);

        $created = false;

        $userData = json_decode($data->getBody()->getContents(), true);
        $phoneNumber = $userData['users'][0]['phoneNumber'];
        if ($phoneNumber) {
            $phoneCredentials = $credentialsRepository->findByPhoneNumber($phoneNumber);
            if (!$phoneCredentials) {
                $user = new User(null);
                $userRepository->add($user);
                $phoneCredentials = new Credentials($user->id(), $phoneNumber);
                $credentialsRepository->add($phoneCredentials);
                $flusher->flush();
                $created = true;
            }
            $user = $userRepository->find($phoneCredentials->id());
            return $authenticator->authenticate($request, $user)->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_ACCEPTED);
        }
        return null;
    }

    /**
     * @Route(path="/api/accessToken/phone", methods={"POST"})
     * @param Request $request
     * @param PhoneAuthData $authData
     * @param CredentialsRepository $credentialsRepository
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     * @param UserAuthenticator $authenticator
     * @return Response|null
     * @throws \Exception
     * @Post(
     *     path="/api/accessToken/phone",
     *     summary="Получение токена доступа через firebase",
     *     tags={"Токены"},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(type="string", property="idToken")
     *         )
     *     ),
     *     @\OpenApi\Annotations\Response(response="201", description="Аутентифицирован новый пользователь", @JsonContent(@Property(type="string", property="token"))),
     *     @\OpenApi\Annotations\Response(response="200", description="Аутентифицирован существующий пользователь", @JsonContent(@Property(type="string", property="token"))),
     * )
     */
    public function phoneAccessToken(Request $request, PhoneAuthData $authData, CredentialsRepository $credentialsRepository, UserRepository $userRepository, Flusher $flusher, UserAuthenticator $authenticator)
    {
        $client = new Client([
            'query' => [
                'key' => $this->firebaseApiKey
            ]
        ]);
        $data = null;

        $data = $client->post('https://www.googleapis.com/identitytoolkit/v3/relyingparty/getAccountInfo', [
            'json' => ['idToken' => $authData->idToken]
        ]);

        $created = false;

        $userData = json_decode($data->getBody()->getContents(), true);
        $phoneNumber = $userData['users'][0]['phoneNumber'];
        if ($phoneNumber) {
            $phoneCredentials = $credentialsRepository->findByPhoneNumber($phoneNumber);
            if (!$phoneCredentials) {
                $user = new User(null);
                $userRepository->add($user);
                $phoneCredentials = new Credentials($user->id(), $phoneNumber);
                $credentialsRepository->add($phoneCredentials);
                $flusher->flush();
                $created = true;
            }
            $user = $userRepository->find($phoneCredentials->id());
            return $authenticator->authenticate($request, $user)->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
        }
        return null;
    }
}
