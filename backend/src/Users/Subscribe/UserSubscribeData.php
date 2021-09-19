<?php

namespace App\Users\Subscribe;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 *
 * @ODM()
 * @DiscriminatorMap(typeProperty="type", mapping={
 *    "objectCategory"="App\Users\Subscribe\Data\ObjectCategorySubscribeData",
 * })
 */
interface UserSubscribeData
{
}