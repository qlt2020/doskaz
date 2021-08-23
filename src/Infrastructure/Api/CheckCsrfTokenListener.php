<?php


namespace App\Infrastructure\Api;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class CheckCsrfTokenListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

        if ($request->headers->has('Authorization')) {
            return;
        }

        if ($request->cookies->has('XSRF-TOKEN')
            && $request->headers->contains('X-XSRF-TOKEN', $request->cookies->get('XSRF-TOKEN'))) {
            return;
        }

        if ($request->isMethod('post') && !($request->getContentType() === 'json' || $request->headers->get('Content-Type') === 'application/octet-stream')) {
            throw new UnsupportedMediaTypeHttpException('Unsupported format');
        } else {
            return;
        }

        throw new AccessDeniedHttpException('Access denied');
    }
}
