<?php


namespace App\Infrastructure\ObjectResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ObjectArgumentResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_subclass_of($argument->getType(), DataObject::class);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     * @throws ValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json', [
        //    ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
        ]);
        $constraints = $this->validator->validate($data);
        if ($constraints->count() > 0) {
            throw new ValidationException($constraints);
        }
        yield $data;
    }
}
