<?php


namespace App\UserEvents\LevelReached;

use App\UserAbilities\UnlockedAbilityRepository;
use App\UserEvents\Context;
use App\UserEvents\Data;
use App\UserEvents\DataFormatter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LevelReachedDataFormatter implements DataFormatter
{
    private UnlockedAbilityRepository $unlockedAbilityRepository;

    public function __construct(UnlockedAbilityRepository $unlockedAbilityRepository)
    {
        $this->unlockedAbilityRepository = $unlockedAbilityRepository;
    }

    public function supports(Data $data): bool
    {
        return $data instanceof LevelReachedData;
    }

    /**
     * @param Data|LevelReachedData $data
     * @return array
     */
    public function format(Data $data, Context $context): array
    {
        $unlockedAbility = $this->unlockedAbilityRepository->findAbilityForLevel($context->userId, $data->level);

        return [
            'level' => $data->level,
            'pointsUntilNextLevel' => $data->pointsUntilNextLevel,
            'unlockedAbility' => $unlockedAbility ? $unlockedAbility->key() : null
        ];
    }
}
