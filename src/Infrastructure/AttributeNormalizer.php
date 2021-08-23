<?php


namespace App\Infrastructure;

use App\Objects\Adding\Attribute;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AttributeNormalizer extends AbstractNormalizer
{
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return Attribute::fromString($data);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Attribute::class;
    }

    /**
     * @param Attribute $object
     * @param null $format
     * @param array $context
     * @return string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->toString();
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Attribute;
    }
}
