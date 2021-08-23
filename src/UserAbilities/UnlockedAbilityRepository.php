<?php


namespace App\UserAbilities;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UnlockedAbilityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(UnlockedAbility::class);
    }

    public function add(UnlockedAbility $ability)
    {
        $this->entityManager->persist($ability);
    }

    public function findAbilityForLevel(int $userId, int $level): ?UnlockedAbility
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
            'level' => $level
        ]);
    }
}
