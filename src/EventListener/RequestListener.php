<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

// permet de changer la langue du site

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        // dd($request->get('_route'));
        if ($request->get('_route') == 'set_locale') {
            if ($request->getDefaultLocale() == 'fr')
                $request->setLocale('en');
            else
                $request->setLocale('fr');
        }
    }
}
