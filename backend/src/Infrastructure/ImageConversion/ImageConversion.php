<?php


namespace App\Infrastructure\ImageConversion;

class ImageConversion
{
    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }
}
