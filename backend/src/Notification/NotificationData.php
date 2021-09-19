<?php

namespace App\Notification;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;


/**
 * @ODM()
 * @DiscriminatorMap(typeProperty = "type", mapping=\App\Notification\NotificationData::MAPPING)
 */
abstract class NotificationData
{
    const MAPPING = [
        'objectCreated' => ObjectCreatedNotificationData::class
    ];
}