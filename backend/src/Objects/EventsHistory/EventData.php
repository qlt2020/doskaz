<?php


namespace App\Objects\EventsHistory;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @ODM()
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "review_created" = "App\Objects\EventsHistory\Type\ReviewCreated",
 *         "verification_rejected" = "App\Objects\EventsHistory\Type\VerificationRejected",
 *         "verification_confirmed" = "App\Objects\EventsHistory\Type\VerificationConfirmed",
 *         "supplemented" = "App\Objects\EventsHistory\Type\Supplemented",
 *     })
 */
interface EventData
{
}
