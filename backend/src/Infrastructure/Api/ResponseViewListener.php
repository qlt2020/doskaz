<?php
declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class ResponseViewListener
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelView(ViewEvent $event)
    {
        $value = $event->getControllerResult();
        if (is_null($value) || $value === '') {
            $event->setResponse(new Response('', 204));
            return;
        }
        $json = $this->serializer->serialize($value, 'json');
        $response = new JsonResponse($json, 200, [], true);

        $event->setResponse($response);
    }
}
