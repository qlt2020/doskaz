<?php


namespace App\Objects\Zone\Middle;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class KidsAccessibility extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'kidsAccessibility');
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
        else if ($this->isMatchesAll(Attribute::notProvided())) {
            $builder->withKidsNotProvided();
        }

        else if ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())
            && ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::notProvided())
                || $this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::unknown()))
            && !$this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::no())
        ) {
            $builder->withKidsFullAccessible();
        }

        else if ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::no())
            && ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::notProvided())
                || $this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::unknown()))
            && !$this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())
        ) {
            $builder->withKidsNotAccessible();
        }
        else {
            $builder->withKidsPartialAccessible();
        }

        return $builder->build();
    }
}
