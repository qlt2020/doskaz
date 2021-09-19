<?php

namespace App\Notification;

use Ramsey\Uuid\UuidInterface;

class ObjectCreatedNotificationData extends NotificationData
{
    /**
     * @var UuidInterface
     */
    public UuidInterface $objectId;

    public string $title;

    public string $type = 'object_created';

    public function __construct(UuidInterface $objectId, string $title)
    {
        $this->objectId = $objectId;
        $this->title = $title;
    }
}