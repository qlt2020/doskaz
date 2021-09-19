<?php


namespace App\Objects\Zone\Small;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class KidsAccessibility extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('small', 'kidsAccessibility');
    }

    public function calculateScore(): AccessibilityScore
    {
        $builder = AccessibilityScoreBuilder::initNotProvided();

        if ($this->isMatchesAll(Attribute::yes())) {
            $builder->withKidsFullAccessible();
        }
        else if ($this->isMatchesAll(Attribute::no())) {
            $builder->withKidsNotAccessible();
        }
        else if ($this->isMatchesAll(Attribute::unknown())) {
            $builder->withKidsUnknown();
        }
        else if (!$this->isMatchesAll(Attribute::notProvided())) {
            $builder->withKidsNotAccessible();
        }

        return $builder->build();
    }
}
