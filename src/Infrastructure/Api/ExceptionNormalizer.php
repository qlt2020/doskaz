<?php


namespace App\Infrastructure\Api;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExceptionNormalizer implements NormalizerInterface
{
    private $isDebug = false;

    public function __construct(bool $debug = false)
    {
        $this->isDebug = $debug;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'message' => $this->isDebug ? $object->getMessage() : 'Server error',
            'code' => 500
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \Exception;
    }
}
