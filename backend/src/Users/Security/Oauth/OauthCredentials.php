<?php


namespace App\Users\Security\Oauth;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oauth_credentials", uniqueConstraints={
 *        @ORM\UniqueConstraint(name="oauth_credentials_unique",
 *            columns={"network", "identifier"})
 *    })
 */
class OauthCredentials
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $network;

    /**
     * @ORM\Column(type="string")
     */
    private $identifier;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;

    public function id()
    {
        return $this->id;
    }

    public function __construct(int $id, string $network, string $identifier, string $fullName = null)
    {
        $this->id = $id;
        $this->network = $network;
        $this->identifier = $identifier;
        $this->name = $fullName;
        $this->createdAt = new \DateTimeImmutable();
    }
}
