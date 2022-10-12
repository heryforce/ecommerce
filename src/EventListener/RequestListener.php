<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

// permet de changer la langue du site

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($request->get('_route') == 'set_locale') {
            switch ($request->attributes->get('_route_params')['loc']) {
                case 'fr':
                    $request->setLocale('fr');
                    break;
                case 'en':
                    $request->setLocale('en');
                    break;
                default:
                    break;
            }
        }
    }
}
