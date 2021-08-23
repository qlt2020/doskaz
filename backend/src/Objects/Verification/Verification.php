<?php


namespace App\Objects\Verification;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="object_verifications")
 */
class Verification implements EventProducer
{
    use ProducesEvents;

    private const STATUS_NOT_VERIFIED = 'not_verified';
    private const STATUS_FULL_VERIFIED = 'full_verified';
    private const STATUS_PARTIAL_VERIFIED = 'partial_verified';

    public const STATUSES = [
        self::STATUS_NOT_VERIFIED,
        self::STATUS_FULL_VERIFIED,
        self::STATUS_PARTIAL_VERIFIED
    ];

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" = "not_verified"})
     */
    private $status;

    /**
     * Verification constructor.
     * @param $id
     */
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
        $this->status = self::STATUS_NOT_VERIFIED;
    }

    public function confirm(int $userId)
    {
        $this->status = self::STATUS_FULL_VERIFIED;
        $this->userId = $userId;
        $this->remember(new VerificationConfirmed($this->id, $userId));
        $this->remember(new ObjectVerified($this->id, $this->userId));
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function reject(int $userId)
    {
        $this->status = self::STATUS_PARTIAL_VERIFIED;
        $this->userId = $userId;
        $this->remember(new VerificationRejected($this->id, $userId));
        $this->remember(new ObjectVerified($this->id, $this->userId));
        $this->updatedAt = new \DateTimeImmutable();
    }
}
