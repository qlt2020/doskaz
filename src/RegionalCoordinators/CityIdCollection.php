<?php


namespace App\RegionalCoordinators;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Collection\AbstractCollection;

/**
 * @ODM()
 */
class CityIdCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'string';
    }
}
