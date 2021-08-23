<?php


namespace App\Users\Security\Oauth;

use Doctrine\ORM\EntityManagerInterface;

final class OauthCredentialsRepository
{
    private $repository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(OauthCredentials::class);
    }

    public function add(OauthCredentials $credentials)
    {
        $this->entityManager->persist($credentials);
    }

    public function findByNetworkAndIdentifier(string $network, string $identifier): ?OauthCredentials
    {
        return $this->repository->findOneBy([
            'network' => $network,
            'identifier' => $identifier
        ]);
    }
}
