<?php

declare(strict_types=1);

use DI\Container;
use Spoudazon\InkwellCms\Controller\ErrorController;
use Spoudazon\InkwellCms\EventSubscriber\ThemeAssetsSubscriber;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Spoudazon\InkwellCms\Twig\AppExtension;
use Spoudazon\InkwellCms\Twig\AppVariable;
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
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    'app.theme' => factory(fn(Container $c) => $c->get('app.website')['theme'] ?? 'default'),
    'app.root' => dirname(__DIR__),
    'app.cache_dir' => factory(fn(Container $c) => $c->get(AppRuntimeConfig::class)->getCacheDir()),
    'app.routes' => factory(fn() => require __DIR__ . '/routes.php'),
    'app.templates_dir' => DI\string('{app.root}/themes/{app.theme}/templates'),
    'app.website' => factory(fn() => require __DIR__ . '/website.php'),
    'app.public_assets_dir' => DI\string('/assets'),

    RequestContext::class => factory(fn() => new RequestContext()),

    Environment::class => factory(function (Container $c) {
        $twig = new Environment(
            new FilesystemLoader($c->get('app.templates_dir')),
            [
                'cache' => $c->get('app.cache_dir') . '/twig',
                'auto_reload' => $c->get(AppRuntimeConfig::class)->isDebug(),
                'strict_variables' => $c->get(AppRuntimeConfig::class)->isDebug(),
            ]
        );

        $twig->addGlobal('website', $c->get('app.website'));
        $twig->addGlobal('public_assets_dir', $c->get('app.public_assets_dir'));

        $twig->addExtension($c->get(AppExtension::class));

        return $twig;
    }),

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
        $eventDispatcher->addSubscriber($c->get(ThemeAssetsSubscriber::class));
        return $eventDispatcher;
    }),


    EventDispatcherInterface::class => get(EventDispatcher::class),
    ControllerResolverInterface::class => get(ContainerControllerResolver::class),

    RequestStack::class => autowire(),

    // Bind the shared RequestStack explicitly: PHP-DI autowiring would otherwise
    // build the kernel a private instance, so the request it pushes would never
    // be visible to RequestStack consumers.
    HttpKernel::class =>
        autowire()
            ->constructorParameter('requestStack', get(RequestStack::class)),

    AppVariable::class => factory(fn(Container $c) => new AppVariable($c->get(RequestStack::class))),
    AppExtension::class => factory(fn(Container $c) => new AppExtension($c->get(AppVariable::class))),
];
