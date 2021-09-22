<?php

namespace App\Objects;

class AccessibleObjectExportDecorator
{
    public int $movementFullAccess = 0;
    public int $movementPartialAccess = 0;
    public int $movementNotAccess = 0;
    public int $limbFullAccess = 0;
    public int $limbPartialAccess = 0;
    public int $limbNotAccess = 0;
    public int $visionFullAccess = 0;
    public int $visionPartialAccess = 0;
    public int $visionNotAccess = 0;
    public int $hearingFullAccess = 0;
    public int $hearingPartialAccess = 0;
    public int $hearingNotAccess = 0;
    public int $intellectualFullAccess = 0;
    public int $intellectualPartialAccess = 0;
    public int $intellectualNotAccess = 0;
    public int $kidsFullAccess = 0;
    public int $kidsPartialAccess = 0;
    public int $kidsNotAccess = 0;
    
    public function resetGroupFields()
    {
        $this->movementFullAccess = 0;
        $this->movementPartialAccess = 0;
        $this->movementNotAccess = 0;
        $this->limbFullAccess = 0;
        $this->limbPartialAccess = 0;
        $this->limbNotAccess = 0;
        $this->visionFullAccess = 0;
        $this->visionPartialAccess = 0;
        $this->visionNotAccess = 0;
        $this->hearingFullAccess = 0;
        $this->hearingPartialAccess = 0;
        $this->hearingNotAccess = 0;
        $this->intellectualFullAccess = 0;
        $this->intellectualPartialAccess = 0;
        $this->intellectualNotAccess = 0;
        $this->kidsFullAccess = 0;
        $this->kidsPartialAccess = 0;
        $this->kidsNotAccess = 0;
    }
    
    public function setGroupFields($mainCategory)
    {
        $this->movementFullAccess += $mainCategory['movement_full_accessible'];
        $this->movementPartialAccess += $mainCategory['movement_partial_accessible'];
        $this->movementNotAccess += $mainCategory['movement_not_accessible'];
        $this->limbFullAccess += $mainCategory['limb_full_accessible'];
        $this->limbPartialAccess += $mainCategory['limb_partial_accessible'];
        $this->limbNotAccess += $mainCategory['limb_not_accessible'];
        $this->visionFullAccess += $mainCategory['vision_full_accessible'];
        $this->visionPartialAccess += $mainCategory['vision_partial_accessible'];
        $this->visionNotAccess += $mainCategory['vision_not_accessible'];
        $this->hearingFullAccess += $mainCategory['hearing_full_accessible'];
        $this->hearingPartialAccess += $mainCategory['hearing_partial_accessible'];
        $this->hearingNotAccess += $mainCategory['hearing_not_accessible'];
        $this->intellectualFullAccess += $mainCategory['intellectual_full_accessible'];
        $this->intellectualPartialAccess += $mainCategory['intellectual_partial_accessible'];
        $this->intellectualNotAccess += $mainCategory['intellectual_not_accessible'];
        $this->kidsFullAccess += $mainCategory['kids_full_accessible'];
        $this->kidsPartialAccess += $mainCategory['kids_partial_accessible'];
        $this->kidsNotAccess += $mainCategory['kids_not_accessible'];
    }
        
    public function sumGroupCategory($group)
    {
        switch ($group) {
            case 'movement':
            case 'babyCarriage':
                $data = $this->totalAccess($this->movementFullAccess,$this->movementPartialAccess, $this->movementNotAccess);
                break;
            case 'vision':
                $data = $this->totalAccess($this->visionFullAccess,$this->visionPartialAccess, $this->visionNotAccess);
                break;
            case 'limb':
            case 'temporal':
            case 'missingLimbs':
            case 'pregnant':
            case 'agedPeople':    
                $data = $this->totalAccess($this->limbFullAccess,$this->limbPartialAccess, $this->limbNotAccess);
                break;
            case 'hearing':
                $data = $this->totalAccess($this->hearingFullAccess,$this->hearingPartialAccess, $this->hearingNotAccess);
                break;
            case 'intellectual':
                $data = $this->totalAccess($this->intellectualFullAccess,$this->intellectualPartialAccess, $this->intellectualNotAccess);
                break;
            case 'withChild':
                $data = $this->totalAccess($this->kidsFullAccess,$this->kidsPartialAccess, $this->kidsNotAccess);
                break;
            default:
                $data = $this->totalAccess(0,0,0);
                break;
        }
        return $data;
    }
    public function totalAccess($full, $partial, $notAccess)
    {
        return [
            'total_access' => $full + $partial + $notAccess,
            'full_access' => $full,
            'partial_access' => $partial,
            'no_access' => $notAccess,
        ];
    }
}