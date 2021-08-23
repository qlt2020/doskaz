<?php


namespace App\Users;

use App\AdminpanelPermissions\AdminpanelPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminPermissionsVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, AdminpanelPermission::ALL);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return in_array('ROLE_ADMIN', $token->getUser()->getRoles());
    }
}
