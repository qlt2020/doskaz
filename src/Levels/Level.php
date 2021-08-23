<?php


namespace App\Levels;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="levels")
 */
class Level implements EventProducer
{
    use ProducesEvents;

    const MAP = [
        1 => 0,
        2 => 3,
        3 => 6,
        4 => 10,
        5 => 15,
        6 => 25,
        7 => 40,
        8 => 60,
        9 => 90,
        10 => 150
    ];

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $value;

    /**
     * @ORM\Column(type="integer")
     */
    private int $points;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private DateTimeImmutable $updatedAt;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->value = 1;
        $this->points = 0;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function addPoints(int $points)
    {
        $this->points += $points;
        foreach (self::MAP as $level => $threshold) {
            if ($threshold > $this->points || $level <= $this->value) {
                continue;
            }
            $this->value = $level;
            $this->remember(new LevelReached($this->userId, $this->value));
        }
        $this->updatedAt = new DateTimeImmutable();
    }

    public function nextLevelThreshold(): int
    {
        $currentLevelThreshold = self::MAP[$this->value];
        return self::MAP[$this->value + 1] ?? $currentLevelThreshold;
    }

    public function progressToNextLevel(): int
    {
        $currentLevelThreshold = self::MAP[$this->value];
        $nextLevelThreshold = self::MAP[$this->value + 1] ?? $currentLevelThreshold;
        if ($nextLevelThreshold === $currentLevelThreshold) {
            return 100;
        }
        return ($this->points - $currentLevelThreshold) / ($nextLevelThreshold - $currentLevelThreshold) * 100;
    }

    public function pointsToNextLevel(): int
    {
        $nextLevelThreshold = self::MAP[$this->value + 1] ?? 0;
        return $nextLevelThreshold > 0 ? $nextLevelThreshold - $this->points : 0;
    }

    public function value()
    {
        return $this->value;
    }

    public function points()
    {
        return $this->points;
    }
}
