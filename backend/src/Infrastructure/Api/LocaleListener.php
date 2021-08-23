<?php
declare(strict_types=1);

namespace App\Infrastructure\Api;

use Symfony\Component\HttpKernel\Event\RequestEvent;

final class LocaleListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $lang = $request->headers->get('Accept-Language');
        if ($lang) {
            $request->setLocale($lang);
        }
    }
}
