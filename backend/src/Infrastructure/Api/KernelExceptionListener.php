<?php


namespace App\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class KernelExceptionListener
{
    private $serializer;

    private $isDebug;

    public function __construct(SerializerInterface $serializer, bool $isDebug = false)
    {
        $this->serializer = $serializer;
        $this->isDebug = $isDebug;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $normalizedData = $this->serializer->normalize($exception);
        if ($this->isDebug) {
            $normalizedData['exception'] = get_class($exception);
            $normalizedData['stack'] = $exception->getTraceAsString();
        }
        $data = $this->serializer->serialize($normalizedData, 'json');
        $response = new JsonResponse($data, $normalizedData['code'], [], true);
        $event->setResponse($response);
    }
}
