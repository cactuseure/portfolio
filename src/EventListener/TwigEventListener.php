<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class TwigEventListener
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Ajouter la variable globale 'isHomepage' au Twig
        $this->twig->addGlobal('isHomepage', $route === 'homepage');
    }
}