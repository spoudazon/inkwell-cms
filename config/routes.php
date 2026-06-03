<?php

declare(strict_types=1);

use Spoudazon\InkwellCms\Controller\HomeController;
use Spoudazon\InkwellCms\Controller\PageController;
use Spoudazon\InkwellCms\Controller\PostController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('index', new Route(
    path: '/',
    defaults: ['_controller' => HomeController::class],
    methods: ['GET']
));


$routes->add('post', new Route(
    path: '/post/{slug}',
    defaults: ['_controller' => PostController::class],
    methods: ['GET']
));

$routes->add('page', new Route(
    path: '/page/{slug}',
    defaults: ['_controller' => PageController::class],
    methods: ['GET']
));

return $routes;
