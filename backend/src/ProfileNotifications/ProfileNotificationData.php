<?php


namespace App\ProfileNotifications;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @ODM()
 * @DiscriminatorMap(typeProperty = "type", mapping=\App\ProfileNotifications\ProfileNotificationData::MAPPING)
 */
abstract class ProfileNotificationData
{
    const MAPPING = [
        'levelReached' => LevelReachedNotificationData::class,
        'pointsEarned' => PointsEarnedNotificationData::class
    ];
}
