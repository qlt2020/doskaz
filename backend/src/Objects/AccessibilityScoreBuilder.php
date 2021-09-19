<?php


namespace App\Objects;

use App\Objects\Adding\AccessibilityScore;

class AccessibilityScoreBuilder
{
    private string $movement;
    private string $limb;
    private string $vision;
    private string $hearing;
    private string $intellectual;
    private string $kids;

    public static function initPartialAccessible(): self
    {
        $self = new self();
        $self->movement = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $self->limb = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $self->vision = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $self->hearing = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $self->intellectual = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;  
        $self->kids = AccessibilityScore::SCORE_NOT_PROVIDED;

        return $self;
    }

    public static function initUnknown(): self
    {
        $self = new self();
        $self->movement = AccessibilityScore::SCORE_UNKNOWN;
        $self->limb = AccessibilityScore::SCORE_UNKNOWN;
        $self->vision = AccessibilityScore::SCORE_UNKNOWN;
        $self->hearing = AccessibilityScore::SCORE_UNKNOWN;
        $self->intellectual = AccessibilityScore::SCORE_UNKNOWN;
        $self->kids = AccessibilityScore::SCORE_NOT_PROVIDED;

        return $self;
    }

    public static function initNotProvided(): self
    {
        $self = new self();
        $self->movement = AccessibilityScore::SCORE_NOT_PROVIDED;
        $self->limb = AccessibilityScore::SCORE_NOT_PROVIDED;
        $self->vision = AccessibilityScore::SCORE_NOT_PROVIDED;
        $self->hearing = AccessibilityScore::SCORE_NOT_PROVIDED;
        $self->intellectual = AccessibilityScore::SCORE_NOT_PROVIDED;
        $self->kids = AccessibilityScore::SCORE_NOT_PROVIDED;

        return $self;
    }

    public function withMovementFullAccessible(): self
    {
        $this->movement = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withMovementNotAccessible(): self
    {
        $this->movement = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withMovementPartialAccessible(): self
    {
        $this->movement = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withMovementUnknown(): self
    {
        $this->movement = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withMovementNotProvided(): self
    {
        $this->movement = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function withLimbFullAccessible(): self
    {
        $this->limb = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withLimbNotAccessible(): self
    {
        $this->limb = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withLimbPartialAccessible(): self
    {
        $this->limb = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withLimbUnknown(): self
    {
        $this->limb = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withLimbNotProvided(): self
    {
        $this->limb = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function withVisionFullAccessible(): self
    {
        $this->vision = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withVisionNotAccessible(): self
    {
        $this->vision = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withVisionPartialAccessible(): self
    {
        $this->vision = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withVisionUnknown(): self
    {
        $this->vision = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withVisionNotProvided(): self
    {
        $this->vision = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function withHearingFullAccessible(): self
    {
        $this->hearing = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withHearingNotAccessible(): self
    {
        $this->hearing = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withHearingPartialAccessible(): self
    {
        $this->hearing = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withHearingUnknown(): self
    {
        $this->hearing = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withHearingNotProvided(): self
    {
        $this->hearing = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function withIntellectualFullAccessible(): self
    {
        $this->intellectual = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withIntellectualNotAccessible(): self
    {
        $this->intellectual = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withIntellectualPartialAccessible(): self
    {
        $this->intellectual = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withIntellectualUnknown(): self
    {
        $this->intellectual = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withIntellectualNotProvided(): self
    {
        $this->intellectual = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function withKidsFullAccessible(): self
    {
        $this->kids = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        return $this;
    }

    public function withKidsNotAccessible(): self
    {
        $this->kids = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withKidsPartialAccessible(): self
    {
        $this->kids = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        return $this;
    }

    public function withKidsUnknown(): self
    {
        $this->kids = AccessibilityScore::SCORE_UNKNOWN;
        return $this;
    }

    public function withKidsNotProvided(): self
    {
        $this->kids = AccessibilityScore::SCORE_NOT_PROVIDED;
        return $this;
    }

    public function build(): AccessibilityScore
    {
        return AccessibilityScore::new($this->movement, $this->limb, $this->vision, $this->hearing, $this->intellectual, $this->kids);
    }

    public function withCategoryNotAccessible(string $category): self
    {
        $this->{$category} = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        return $this;
    }

    public function withCategoryScore(string $category, string $score): self
    {
        $this->{$category} = $score;
        return $this;
    }
}
