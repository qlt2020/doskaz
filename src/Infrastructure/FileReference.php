<?php


namespace App\Infrastructure;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 */
class FileReference
{
    /**
     * @var string
     */
    public $relativePath;

    public function __toString()
    {
        return 'storage/' . $this->relativePath;
    }

    public function link()
    {
        return '/storage/' . $this->relativePath;
    }

    public function __construct($path)
    {
        $this->relativePath = str_replace('/storage/', '', $path);
    }

    public function equalsTo($other): bool
    {
        return $other instanceof self && $other->relativePath === $this->relativePath;
    }
}
