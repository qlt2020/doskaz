<?php


namespace App\Objects\Adding;

use App\Infrastructure\ObjectResolver\DataObject;
use App\Objects\Zones;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @ODM()
 * @DiscriminatorMap(
 *     typeProperty="form",
 *     mapping={
 *         "middle" = "App\Objects\Adding\MiddleFormRequestData",
 *         "full" = "App\Objects\Adding\FullFormRequestData",
 *         "small" = "App\Objects\Adding\SmallFormRequestData"
 *     }
 * )
 */
interface Form extends DataObject
{
    public function toZones(): Zones;
}
