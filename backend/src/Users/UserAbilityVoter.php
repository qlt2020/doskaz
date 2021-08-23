<?php


namespace App\Users;

use App\Levels\LevelRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserAbilityVoter extends Voter
{
    /**
     * @var LevelRepository
     */
    private $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [UserAbility::STATUS_CHANGE, UserAbility::AVATAR_UPLOAD]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $userId = $token->getUser()->id();
        $level = $this->levelRepository->find($userId);

        switch ($attribute) {
            case UserAbility::AVATAR_UPLOAD:
                return $level->value() >= 7;
            case UserAbility::STATUS_CHANGE:
                return $level->value() >= 5;
            default:
                return false;
        }
    }
}
