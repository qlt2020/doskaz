<?php


namespace App\Infrastructure\Firebase;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ProfileFetcher
{
    private $client;

    public function __construct(string $apiKey)
    {
        $this->client = new Client([
            'query' => [
                'key' => $apiKey
            ]
        ]);
    }

    public function fetch(string $idToken): UserData
    {
        try {
            $data = $this->client->post('https://www.googleapis.com/identitytoolkit/v3/relyingparty/getAccountInfo', [
                'json' => ['idToken' => $idToken]
            ]);
            $users = json_decode($data->getBody()->getContents(), true)['users'][0];
            return new UserData($users['phoneNumber']);
        } catch (ClientException $exception) {
            if ($exception->getCode() !== 400) {
                throw $exception;
            }

            $message = json_decode($exception->getResponse()->getBody()->getContents(), true);
            if ($message['error']['message'] === 'INVALID_ID_TOKEN') {
                throw new InvalidIdToken('Invalid id token');
            }
            throw new Exception($exception->getMessage());
        }
    }
}
