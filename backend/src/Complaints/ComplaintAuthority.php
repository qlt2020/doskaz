<?php
declare(strict_types=1);

namespace App\Complaints;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="complaint_authorities")
 */
class ComplaintAuthority
{
    /**
     * @ORM\GeneratedValue()
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }
}
