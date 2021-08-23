<?php


namespace App\Users\Security;

use Doctrine\ORM\Mapping as ORM;
use Tuupola\Base62;

/**
 * @ORM\Entity()
 * @ORM\Table(name="access_tokens")
 */
class AccessToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * AccessToken constructor.
     * @param $userId
     * @throws \Exception
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->value = (new Base62())->encode(random_bytes(128));
        $this->createdAt = new \DateTimeImmutable();
        $this->expiresAt = new \DateTimeImmutable('now + 1 month');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function expiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function userId()
    {
        return $this->userId;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new \DateTimeImmutable();
    }
}
