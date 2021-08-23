<?php


namespace App\Objects\Zone\Middle;

use App\Objects\Zones;

class MiddleFormZones extends Zones
{
    /**
     * @var Parking
     */
    public $parking;

    /**
     * @var Entrance
     */
    public $entrance1;

    /**
     * @var Entrance|null
     */
    public $entrance2;

    /**
     * @var Entrance|null
     */
    public $entrance3;

    /**
     * @var Movement
     */
    public $movement;

    /**
     * @var Service
     */
    public $service;

    /**
     * @var Toilet
     */
    public $toilet;

    /**
     * @var Navigation
     */
    public $navigation;

    /**
     * @var ServiceAccessibility
     */
    public $serviceAccessibility;

    /**
     * @var KidsAccessibility
     */
    public $kidsAccessibility;

    /**
     * Zones constructor.
     * @param Parking $parking
     * @param Entrance $entrance1
     * @param Entrance|null $entrance2
     * @param Entrance|null $entrance3
     * @param Movement $movement
     * @param Service $service
     * @param Toilet $toilet
     * @param Navigation $navigation
     * @param ServiceAccessibility $serviceAccessibility
     * @param KidsAccessibility|null $kidsAccessibility
     */
    public function __construct(
        Parking $parking,
        Entrance $entrance1,
        ?Entrance $entrance2,
        ?Entrance $entrance3,
        Movement $movement,
        Service $service,
        Toilet $toilet,
        Navigation $navigation,
        ServiceAccessibility $serviceAccessibility,
        ?KidsAccessibility $kidsAccessibility = null
    ) {
        $this->parking = $parking;
        $this->entrance1 = $entrance1;
        $this->entrance2 = $entrance2;
        $this->entrance3 = $entrance3;
        $this->movement = $movement;
        $this->service = $service;
        $this->toilet = $toilet;
        $this->navigation = $navigation;
        $this->serviceAccessibility = $serviceAccessibility;
        $this->kidsAccessibility = $kidsAccessibility ?? new KidsAccessibility();
    }
}
