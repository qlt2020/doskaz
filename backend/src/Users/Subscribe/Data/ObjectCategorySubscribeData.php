<?php

namespace App\Users\Subscribe\Data;

use App\Users\Subscribe\UserSubscribeData;
use OpenApi\Annotations\Property;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations\Schema;
use App\Users\User;

/**
 * @Schema(title="Подписка на категорию объекта", schema="ObjectCategorySubscribeData")
 */
class ObjectCategorySubscribeData implements UserSubscribeData
{
    /**
     * @Assert\NotBlank()
     * @Assert\Choice(choices=User::USER_CATEGORIES, message="Choose a valid category.")
     * @Property(description="Категория объекта")
     */
    public string $category;
}