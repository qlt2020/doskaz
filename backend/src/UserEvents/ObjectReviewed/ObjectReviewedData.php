<?php


namespace App\UserEvents\ObjectReviewed;

use App\UserEvents\Data;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ODM()
 */
class ObjectReviewedData extends Data
{
    /**
     * @var integer
     */
    public $reviewerId;

    /**
     * @var UuidInterface
     */
    public $objectId;

    /**
     * ObjectReviewed constructor.
     * @param int $reviewerId
     * @param UuidInterface $objectId
     */
    public function __construct(int $reviewerId, UuidInterface $objectId)
    {
        $this->reviewerId = $reviewerId;
        $this->objectId = $objectId;
    }
}
