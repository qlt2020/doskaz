<?php


namespace App\Objects\PhotosHistory;

use App\Infrastructure\FileReference;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="objects_photos_history")
 */
class PhotosHistory
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $objectId;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type=App\Infrastructure\FileReference::class, options={"jsonb" = true})
     */
    private $file;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    public function __construct(int $objectId, FileReference $file, ?int $userId)
    {
        $this->date = new \DateTimeImmutable();
        $this->file = $file;
        $this->objectId = $objectId;
        $this->userId = $userId;
    }

    public function file(): FileReference
    {
        return $this->file;
    }
}
