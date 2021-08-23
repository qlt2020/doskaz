<?php


namespace App\Awards;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="awards")
 */
class Award implements EventProducer
{
    public const TYPE_BRONZE = 'bronze';
    public const TYPE_SILVER = 'silver';
    public const TYPE_GOLD = 'gold';

    public const TYPES = [
        self::TYPE_BRONZE,
        self::TYPE_SILVER,
        self::TYPE_GOLD
    ];

    use ProducesEvents;

    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type="text")
     */
    private string $title;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $issuedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $issuedBy;

    /**
     * @ORM\Column(type="string")
     */
    public string $type;

    public function __construct(int $userId, string $title, ?int $issuedBy)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
        $this->title = $title;
        $this->issuedBy = $issuedBy;
        $this->issuedAt = new \DateTimeImmutable();
        $this->remember(new AwardIssued($this->id, $this->userId));
    }

    public static function fromAwardData(AwardData $awardData, int $userId, ?int $issuedBy = null): self
    {
        $self = new self($userId, $awardData->title, $issuedBy);
        $self->type = $awardData->type;
        return $self;
    }

    public static function bronze(int $userId, string $title, ?int $issuedBy = null): self
    {
        $self = new self($userId, $title, $issuedBy);
        $self->type = self::TYPE_BRONZE;
        return $self;
    }

    public static function silver(int $userId, string $title, ?int $issuedBy = null): self
    {
        $self = new self($userId, $title, $issuedBy);
        $self->type = self::TYPE_SILVER;
        return $self;
    }

    public static function gold(int $userId, string $title, ?int $issuedBy = null): self
    {
        $self = new self($userId, $title, $issuedBy);
        $self->type = self::TYPE_GOLD;
        return $self;
    }
}
