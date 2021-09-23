<?php


namespace App\Objects\Reviews;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="object_reviews")
 */
class Review implements EventProducer
{
    use ProducesEvents;

    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private $id;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $objectId;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $authorId;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isPublished = 0;

    /**
     * Review constructor.
     * @param int $objectId
     * @param string $text
     * @param int $authorId
     * @throws \Exception
     */
    public function __construct(int $objectId, string $text, int $authorId)
    {
        $this->id = Uuid::uuid4();
        $this->objectId = $objectId;
        $this->text = $text;
        $this->authorId = $authorId;
        $this->createdAt = new \DateTimeImmutable();
        $this->remember(new Event\ReviewCreated($this->id, $this->objectId, $this->authorId));
    }

    public function update(ReviewData $reviewData)
    {
        $this->text = $reviewData->text;
    }

    public function accept()
    {
        $this->isPublished = 1;
    }

    public function decline()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}
