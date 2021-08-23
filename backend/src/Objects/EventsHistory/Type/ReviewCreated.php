<?php


namespace App\Objects\EventsHistory\Type;

use App\Objects\EventsHistory\EventData;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Uuid\Uuid;

/**
 * @ODM()
 */
class ReviewCreated implements EventData
{
    public $reviewId;

    public function __construct($reviewId)
    {
        $this->reviewId = $reviewId;
    }
}
