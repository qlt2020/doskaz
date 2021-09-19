<?php


namespace App\UserEvents\AwardIssued;

use App\UserEvents\Data;
use Ramsey\Uuid\UuidInterface;

class AwardIssuedData extends Data
{
    public UuidInterface $awardId;
    public string $type = 'award_issued';

    /**
     * AwardIssuedData constructor.
     * @param UuidInterface $awardId
     */
    public function __construct(UuidInterface $awardId)
    {
        $this->awardId = $awardId;
    }
}
