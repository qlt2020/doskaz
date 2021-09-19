<?php


namespace App\Objects\EventsHistory\Type;

use App\Objects\EventsHistory\EventData;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Uuid\Uuid;

/**
 * @ODM()
 */
class VerificationConfirmed implements EventData
{
    public string $type = 'verification_confirmed';
}
