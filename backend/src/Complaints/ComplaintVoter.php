<?php


namespace App\Complaints;

use App\Users\Security\AuthenticatedUser;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ComplaintVoter extends Voter
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function supports($attribute, $subject)
    {
        return $attribute === ComplaintPermissions::PDF_EXPORT;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
         * @var AuthenticatedUser|null $user
         */
        $user = $token->getUser();
        if (!$user || !$user instanceof AuthenticatedUser) {
            return false;
        }

        $complainantId = $this->connection->fetchColumn('select complainant_id from complaints where id = :id', ['id' => $subject]);

        switch ($attribute) {
            case ComplaintPermissions::PDF_EXPORT:
                return in_array('ROLE_ADMIN', $user->getRoles()) || !empty($complainantId) && $user->id() == $complainantId;
        }
        return false;
    }
}
