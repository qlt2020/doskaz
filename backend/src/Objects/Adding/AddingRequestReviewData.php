<?php


namespace App\Objects\Adding;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class AddingRequestReviewData implements DataObject
{
    public $id;

    /**
     * @Assert\Valid()
     * @var Form
     */
    public $form;

    /**
     * @var \DateTimeImmutable|null
     */
    public $approvedAt;

    /**
     * AddingRequestReviewData constructor.
     * @param $id
     * @param Form $form
     * @param \DateTimeImmutable $approvedAt
     */
    public function __construct($id, Form $form, ?\DateTimeImmutable $approvedAt)
    {
        $this->id = $id;
        $this->form = $form;
        $this->approvedAt = $approvedAt;
    }
}
