<?php


namespace App\Objects\Adding;

use App\Infrastructure\ObjectResolver\DataObject;
use App\Objects\Adding\Steps\EntranceStep;
use App\Objects\Adding\Steps\FirstStep;
use App\Objects\Adding\Steps\KidsAccessibilityStep;
use App\Objects\Adding\Steps\MovementStep;
use App\Objects\Adding\Steps\NavigationStep;
use App\Objects\Adding\Steps\ParkingStep;
use App\Objects\Adding\Steps\ServiceAccessibilityStep;
use App\Objects\Adding\Steps\ServiceStep;
use App\Objects\Adding\Steps\ToiletStep;
use App\Objects\Zone\Middle\Entrance;
use App\Objects\Zone\Middle\KidsAccessibility;
use App\Objects\Zone\Middle\MiddleFormZones;
use App\Objects\Zone\Middle\Movement;
use App\Objects\Zone\Middle\Navigation;
use App\Objects\Zone\Middle\Parking;
use App\Objects\Zone\Middle\Service;
use App\Objects\Zone\Middle\ServiceAccessibility;
use App\Objects\Zone\Middle\Toilet;
use App\Objects\Zones;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MiddleFormRequestData
 * @package App\Objects\Adding
 */
final class MiddleFormRequestData implements DataObject, Form
{
    public string $form = 'middle';
    /**
     * @var FirstStep|null
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    public $first;

    /**
     * @Assert\Valid()
     * @Assert\NotBlank()
     * @var ParkingStep|null
     */
    public $parking;

    /**
     * @var EntranceStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $entrance1;

    /**
     * @var EntranceStep|null
     * @Assert\Valid()
     */
    public $entrance2;

    /**
     * @var EntranceStep|null
     * @Assert\Valid()
     */
    public $entrance3;

    /**
     * @var MovementStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $movement;

    /**
     * @var ServiceStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $service;

    /**
     * @var ToiletStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $toilet;

    /**
     * @var NavigationStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $navigation;

    /**
     * @var ServiceAccessibilityStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $serviceAccessibility;

    /**
     * @var KidsAccessibilityStep|null
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $kidsAccessibility;

    public function toZones(): Zones
    {
        return new MiddleFormZones(
            new Parking($this->parking->attributes, $this->parking->overriddenScore),
            new Entrance($this->entrance1->attributes, $this->entrance1->overriddenScore),
            $this->entrance2 ? new Entrance($this->entrance2->attributes, $this->entrance2->overriddenScore) : null,
            $this->entrance3 ? new Entrance($this->entrance3->attributes, $this->entrance3->overriddenScore) : null,
            new Movement($this->movement->attributes, $this->movement->overriddenScore),
            new Service($this->service->attributes, $this->service->overriddenScore),
            new Toilet($this->toilet->attributes, $this->toilet->overriddenScore),
            new Navigation($this->navigation->attributes, $this->navigation->overriddenScore),
            new ServiceAccessibility($this->serviceAccessibility->attributes, $this->serviceAccessibility->overriddenScore),
            new KidsAccessibility($this->kidsAccessibility->attributes, $this->kidsAccessibility->overriddenScore),
        );
    }
}
