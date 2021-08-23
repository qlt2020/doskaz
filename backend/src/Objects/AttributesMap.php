<?php


namespace App\Objects;

use App\Objects\Adding\Attribute;
use Ramsey\Collection\Map\AbstractTypedMap;

class AttributesMap extends AbstractTypedMap
{
    public function getKeyType(): string
    {
        return 'string';
    }

    public function getValueType(): string
    {
        return Attribute::class;
    }
}
