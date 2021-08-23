<?php
declare(strict_types=1);

namespace App\Blog;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Slug
{
    /**
     * @ORM\Column(type="text", unique=true)
     */
    public $value;

    public static function fromRaw(string $value): self
    {
        $self = new self;
        $self->value = $value;
        return $self;
    }

    private function __construct()
    {
    }
}
