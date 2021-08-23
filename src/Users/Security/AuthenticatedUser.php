<?php


namespace App\Users\Security;

use Symfony\Component\Security\Core\User\UserInterface;

final class AuthenticatedUser implements UserInterface
{
    private $id;

    private $name;

    private $roles;

    public function id()
    {
        return $this->id;
    }

    public function __construct(int $id, string $name, array $roles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->name;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
