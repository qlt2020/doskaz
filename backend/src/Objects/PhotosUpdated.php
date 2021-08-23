<?php


namespace App\Objects;

use App\Infrastructure\FileReferenceCollection;
use Ramsey\Uuid\UuidInterface;

class PhotosUpdated
{
    /**
     * @var UuidInterface
     */
    public $uuid;

    /**
     * @var FileReferenceCollection
     */
    public $oldPhotos;

    /**
     * @var FileReferenceCollection
     */
    public $newPhotos;

    /**
     * PhotosAdded constructor.
     * @param UuidInterface $uuid
     * @param FileReferenceCollection $oldPhotos
     * @param FileReferenceCollection $newPhotos
     */
    public function __construct(UuidInterface $uuid, FileReferenceCollection $oldPhotos, FileReferenceCollection $newPhotos)
    {
        $this->uuid = $uuid;
        $this->oldPhotos = clone $oldPhotos;
        $this->newPhotos = clone $newPhotos;
    }
}
