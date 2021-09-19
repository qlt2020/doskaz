<?php


namespace App\Objects\Adding;

use App\Infrastructure\ObjectResolver\DataObject;
use App\Objects\Adding\Steps\FirstStep;
use App\Objects\Adding\Steps\Full\Entrance;
use App\Objects\Adding\Steps\Full\KidsAccessibility;
use App\Objects\Adding\Steps\Full\Movement;
use App\Objects\Adding\Steps\Full\Navigation;
use App\Objects\Adding\Steps\Full\Parking;
use App\Objects\Adding\Steps\Full\Service;
use App\Objects\Adding\Steps\Full\ServiceAccessibility;
use App\Objects\Adding\Steps\Full\Toilet;
use App\Objects\Zone\Full\FullFormZones;
use App\Objects\Zones;
use Symfony\Component\Validator\Constraints as Assert;

final class FullFormRequestData implements DataObject, Form
{
    public string $form = 'full';
    /**
     * @var FirstStep|null
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    public $first;

    /**
     * @var Parking
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    public $parking;

    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @var Entrance
     */
    public $entrance1;

    /**
     * @var Entrance|null
     * @Assert\Valid()
     */
    public $entrance2;

    /**
     * @var Entrance|null
     * @Assert\Valid()
     */
    public $entrance3;

    /**
     * @var Movement
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $movement;

    /**
     * @var Service
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $service;

    /**
     * @var Toilet
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $toilet;

    /**
     * @var Navigation
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $navigation;

    /**
     * @var ServiceAccessibility
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $serviceAccessibility;

    /**
     * @var KidsAccessibility
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $kidsAccessibility;

    public function toZones(): Zones
    {
        return new FullFormZones(
            new \App\Objects\Zone\Full\Parking($this->parking->attributes, $this->parking->overriddenScore),
            new \App\Objects\Zone\Full\Entrance($this->entrance1->attributes, $this->entrance1->overriddenScore),
            $this->entrance2 ? new \App\Objects\Zone\Full\Entrance($this->entrance2->attributes, $this->entrance2->overriddenScore) : null,
            $this->entrance3 ? new \App\Objects\Zone\Full\Entrance($this->entrance3->attributes, $this->entrance3->overriddenScore) : null,
            new \App\Objects\Zone\Full\Movement($this->movement->attributes, $this->movement->overriddenScore),
            new \App\Objects\Zone\Full\Service($this->service->attributes, $this->service->overriddenScore),
            new \App\Objects\Zone\Full\Toilet($this->toilet->attributes, $this->toilet->overriddenScore),
            new \App\Objects\Zone\Full\Navigation($this->navigation->attributes, $this->navigation->overriddenScore),
            new \App\Objects\Zone\Full\ServiceAccessibility($this->serviceAccessibility->attributes, $this->serviceAccessibility->overriddenScore),
            new \App\Objects\Zone\Full\KidsAccessibility($this->kidsAccessibility->attributes, $this->kidsAccessibility->overriddenScore)
        );
    }
}
