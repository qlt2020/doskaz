<?php
declare(strict_types=1);

namespace App\Complaints;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Schema(title="Жалоба на отсутствие доступа", schema="ComplaintContent2")
 */
final class ComplaintType2 extends ComplaintContent
{
    /**
     * @var boolean
     * @Property(description="Угроза причинения вреда жизни")
     */
    public $threatToLife = false;

    /**
     * @var string|null
     * @Property(description="Другое", nullable=true)
     */
    public $comment;

    /**
     * @var string[]
     * @Property(type="array", @Items(type="string"))
     */
    public $options;

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (!count($this->options) && empty($this->comment)) {
            $context->buildViolation('This value should not be blank.')
                ->atPath('comment')
                ->addViolation();
        }
    }
}
