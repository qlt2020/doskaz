<?php


namespace App\RegionalCoordinators;

use App\AdminpanelPermissions\AdminpanelPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RegionalCoordinatorAdminPermissionsVoter extends Voter
{
    /**
     * @var RegionalCoordinatorRepository
     */
    private RegionalCoordinatorRepository $repository;

    public function __construct(RegionalCoordinatorRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [AdminpanelPermission::ACCESS, AdminpanelPermission::OBJECTS_ACCESS, AdminpanelPermission::ADDING_REQUESTS_ACCESS]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $regionalCoordinator = $this->repository->findByUserId($token->getUser()->id());
        return !empty($regionalCoordinator);
    }
}
