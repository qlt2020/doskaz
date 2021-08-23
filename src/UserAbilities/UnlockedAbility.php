<?php


namespace App\UserAbilities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unlocked_abilities")
 */
class UnlockedAbility
{
    public const ABILITY_STATUS_CHANGE = 'status_change';
    public const ABILITY_AVATAR_UPLOAD = 'avatar_upload';

    public const ABILITIES_MAP = [
        5 => self::ABILITY_STATUS_CHANGE,
        7 => self::ABILITY_AVATAR_UPLOAD,
    ];

    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $key;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $level;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $date;

    public function __construct(int $userId, int $level, string $abilityKey)
    {
        $this->id = Uuid::uuid4();
        $this->date = new \DateTimeImmutable();
        $this->userId = $userId;
        $this->level = $level;
        $this->key = $abilityKey;
    }

    public function key()
    {
        return $this->key;
    }
}
