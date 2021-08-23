<?php
declare(strict_types=1);

namespace App\Complaints;

use App\Infrastructure\ObjectResolver\DataObject;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Schema(title="Жалоба", schema="Complaint")
 */
final class ComplaintData implements DataObject
{
    /**
     * @var Complainant
     * @Assert\Valid()
     * @Assert\NotBlank()
     * @Property(ref="#/components/schemas/Complainant")
     */
    public $complainant;

    /**
     * @Property(nullable=false, type={"string"}, description="Id органа обращения")
     * @var string|int|null
     * @Assert\NotBlank()
     */
    public $authorityId;

    /**
     * @var boolean
     * @Property(nullable=false, default=false, description="Запомнить данные для дальнейшего использования")
     */
    public $rememberPersonalData = false;

    /**
     * @var int|string|null
     * @Property(nullable=true, type="integer", description="Id объекта", example=0)
     */
    public $objectId;

    /**
     * @var ComplaintContent
     * @Assert\Valid()
     * @Assert\NotBlank()
     * @Property(ref="#/components/schemas/AbstractComplaintContent")
     */
    public $content;

    /**
     * ComplaintData constructor.
     * @param Complainant $complainant
     * @param int|string|null $authorityId
     * @param bool $rememberPersonalData
     * @param int|string|null $objectId
     * @param ComplaintContent $content
     */
    public function __construct(?Complainant $complainant = null, ?ComplaintContent $content = null, $authorityId = null, bool $rememberPersonalData = true, $objectId = null)
    {
        $this->complainant = $complainant;
        $this->authorityId = $authorityId;
        $this->rememberPersonalData = $rememberPersonalData;
        $this->objectId = $objectId;
        $this->content = $content;
    }
}
