<?php


namespace App\Objects\PhotosAdding\Event;

use App\Infrastructure\FileReferenceCollection;
use Ramsey\Uuid\UuidInterface;

class PhotosAddingRequestApproved
{
    public UuidInterface $requestId;

    public int $objectId;

    public FileReferenceCollection $photos;

    public int $createdBy;

    /**
     * PhotosAddingRequestApproved constructor.
     * @param UuidInterface $requestId
     * @param int $objectId
     * @param int $createdBy
     * @param FileReferenceCollection $photos
     */
    public function __construct(UuidInterface $requestId, int $objectId, int $createdBy, FileReferenceCollection $photos)
    {
        $this->requestId = $requestId;
        $this->objectId = $objectId;
        $this->photos = $photos;
        $this->createdBy = $createdBy;
    }
}
