<?php


namespace App\Infrastructure\Storage;

use Hoa\Mime\Mime;
use League\Flysystem\FilesystemInterface;

class Storage
{
    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $defaultStorage)
    {
        $this->filesystem = $defaultStorage;
    }

    public function uploadFile($resource): string
    {
        $name = bin2hex(random_bytes(16));
        $this->filesystem->assertAbsent($name);
        $this->filesystem->writeStream($name, $resource);
        $mime = $this->filesystem->getMimetype($name);
        $extensions = Mime::getExtensionsFromMime($mime);
        $nameWithExtension = $name . '.' . $extensions[0];
        $this->filesystem->rename($name, $nameWithExtension);
        return $nameWithExtension;
    }
}
