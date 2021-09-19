<?php

namespace App\Infrastructure\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileUploader
{
    private Filesystem $filesystem;
    public function __construct(Filesystem $filesystem){
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file): array
    {
        $name = bin2hex(random_bytes(16));
        $fileName = $name.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move('../storage', $fileName);
            return [
                'status' => true,
                'path' => '/storage/'.$fileName
            ];
        } catch (FileException $e) {
            return [ 'status' => false ];
        }
    }

    public function remove(string $path)
    {
        $this->filesystem->remove($path);
    }
}