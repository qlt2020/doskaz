<?php


namespace App\Infrastructure;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class FileNormalizer extends AbstractNormalizer
{
    /**
     * @var FilesystemInterface
     */
    private $storage;

    public function __construct(FilesystemInterface $defaultStorage)
    {
        parent::__construct();
        $this->storage = $defaultStorage;
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return new FileReference($data);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === FileReference::class;
    }

    /**
     * @param FileReference $object
     * @param null $format
     * @param array $context
     * @return string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->link();
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FileReference;
    }
}
