<?php


namespace App\Feedback;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="feedback")
 */
class Feedback
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $email;

    /**
     * @ORM\Column(type="text")
     */
    private string $text;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $deletedAt;

    /**
     * Feedback constructor.
     * @param string $name
     * @param string $email
     * @param string $text
     * @throws \Exception
     */
    public function __construct(string $name, string $email, string $text)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->email = $email;
        $this->text = $text;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function delete()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}
