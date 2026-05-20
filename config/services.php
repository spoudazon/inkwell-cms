<?php

declare(strict_types=1);

use DI\Container;
use Spoudazon\InkwellCms\Controller\ErrorController;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    'app.root' => dirname(__DIR__),
    'app.cache_dir' => factory(fn(Container $c) => $c->get(AppRuntimeConfig::class)->getCacheDir()),
    'app.routes' => factory(fn() => require __DIR__ . '/routes.php'),

    RequestContext::class => factory(fn() => new RequestContext()),

    HtmlErrorRenderer::class => factory(fn(Container $c) => new HtmlErrorRenderer(
        debug: $c->get(AppRuntimeConfig::class)->isDebug(),
    )),

    UrlMatcher::class => factory(function (Container $c) {
        return new UrlMatcher($c->get('app.routes'), $c->get(RequestContext::class));
    }),

    RouterListener::class => factory(function (Container $c) {
        return new RouterListener(
            $c->get(UrlMatcher::class),
            $c->get(RequestStack::class),
            $c->get(RequestContext::class)
        );
    }),

    ErrorListener::class => factory(fn(Container $c) => new ErrorListener(
        controller: $c->get(ErrorController::class),
        logger: null,
        debug: $c->get(AppRuntimeConfig::class)->isDebug(),
        exceptionsMapping: [],
    )),

    EventDispatcher::class => factory(function (Container $c) {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($c->get(RouterListener::class));
        $eventDispatcher->addSubscriber($c->get(ErrorListener::class));
        return $eventDispatcher;
    }),

    EventDispatcherInterface::class => get(EventDispatcher::class),
    ControllerResolverInterface::class => get(ContainerControllerResolver::class),

    RequestStack::class => autowire(),
    HttpKernel::class => autowire()
];
