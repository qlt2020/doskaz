<?php
declare(strict_types=1);

namespace App\Blog;

use Cocur\Slugify\Slugify;

final class SlugFactory
{
    private $slugify;

    public function __construct()
    {
        $this->slugify = new Slugify(['rulesets' => ['default', 'russian']]);
    }

    public function make(string $text): Slug
    {
        return Slug::fromRaw($this->slugify->slugify($text));
    }
}
