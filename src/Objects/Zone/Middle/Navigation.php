<?php


namespace App\Objects\Zone\Middle;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Navigation extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'navigation');
    }

    public function calculateScore(): AccessibilityScore
    {
        if ($this->isMatchesAll(Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }
        if ($this->isMatchesAll(Attribute::unknown())) {
            return AccessibilityScore::unknown();
        }
        if ($this->isMatchesAll(Attribute::notProvided())) {
            return AccessibilityScore::notProvided();
        }


        $scoreBuilder = AccessibilityScoreBuilder::initPartialAccessible();

        if ($this->isMatches([1], Attribute::yes())) {
            $scoreBuilder->withMovementFullAccessible()
                ->withLimbFullAccessible();
        }

        if ($this->isMatches([1, 15], Attribute::yes())) {
            $scoreBuilder->withIntellectualFullAccessible();
        }

        if ($this->isMatches([1, 13], Attribute::yes())) {
            $scoreBuilder->withHearingFullAccessible();
        }

        if ($this->isMatches([1, 4, 5, 6], Attribute::yes())) {
            $scoreBuilder->withVisionFullAccessible();
        }

        if ($this->isMatches([1], Attribute::no())) {
            $scoreBuilder->withMovementNotAccessible()
                ->withLimbNotAccessible();
        }

        if ($this->isMatches([1, 15], Attribute::no())) {
            $scoreBuilder->withIntellectualNotAccessible();
        }

        if ($this->isMatches([1, 13], Attribute::no())) {
            $scoreBuilder->withHearingNotAccessible();
        }

        if ($this->isMatches([1, 4, 5, 6], Attribute::no())) {
            $scoreBuilder->withVisionNotAccessible();
        }

        return $scoreBuilder->build();
    }
}
