<?php

namespace App\Notification;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use OpenApi\Annotations\Discriminator;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM()
 * @DiscriminatorMap(typeProperty="type", mapping={
 *     "objectCategory"="App\Users\Subscribe\Data\ObjectCategorySubscribeData",
 * })
 * @Schema(
 *     title="Содержимое уведомления",
 *     schema="SubscribeContent",
 *     oneOf={
 *         @Schema(ref="#/components/schemas/ObjectCategorySubscribeData")
 *     },
 *     discriminator={
 *         @Discriminator(
 *             propertyName="type",
 *             mapping={
 *                 "objectCategory"="#/components/schemas/ObjectCategorySubscribeData",
 *             }
 *         )
 *     }
 * )
 */
class SubscribeContent
{
    /**
    * @Assert\Choice({"objectCategory"})
    * @Property(example="objectCategory")
    */
    public $type;
    /**
     * @Assert\NotBlank()
     * @Property(type="string", example = "intellectual")
     */
    public string $category;

    /**
     * SubscribeContent constructor.
     * @param string $type
     */
    public function __construct(string $type, string $category)
    {
        $this->type = $type;
        $this->category = $category;
    }
}