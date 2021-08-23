<?php


namespace App\Infrastructure\Api;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HttpExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param HttpExceptionInterface|\Exception $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'message' => $object->getMessage(),
            'code' => $object->getStatusCode()
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof HttpExceptionInterface;
    }
}
