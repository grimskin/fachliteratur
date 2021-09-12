<?php


namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class JsonResponseFormatterListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
