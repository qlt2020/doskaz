<?php
declare(strict_types=1);

namespace App\Infrastructure\ObjectResolver;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class ValidationExceptionNormalizer implements NormalizerInterface
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param ValidationException $object
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string|void
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'message' => 'Validation Failed',
            'code' => 400,
            //'errors' => $object->constraintViolationList(),
            'errors' => $this->requestStack->getCurrentRequest()->query->has('validationErrorsOld') ? $object->constraintViolationList() : array_map(function (ConstraintViolation $constraintViolation) {
                return [
                    'property' => $constraintViolation->getPropertyPath(),
                    'message' => $constraintViolation->getMessage()
                ];
            }, iterator_to_array($object->constraintViolationList()))
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ValidationException;
    }
}
