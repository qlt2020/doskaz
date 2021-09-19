<?php
declare(strict_types=1);
namespace App\Notification;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Property;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 * @Schema(title="Уведомление", schema="Subscribe")
 */
class SubscribeData implements DataObject
{
    /**
     * @var SubscribeContent

     * @Assert\Valid()
     * @Assert\NotBlank()
     * @Property(ref="#/components/schemas/SubscribeContent")
     */
    public $content;

}