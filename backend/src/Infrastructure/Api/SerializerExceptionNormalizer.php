<?php
declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SerializerExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param NotNormalizableValueException $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'message' => 'Bad Request',
            'code' => 400
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof NotNormalizableValueException;
    }
}
