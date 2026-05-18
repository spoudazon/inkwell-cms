<?php

declare(strict_types=1);

use Spoudazon\InkwellCms\Controller\HomeController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('index', new Route(
    path: '/',
    defaults: ['_controller' => HomeController::class],
    methods: ['GET']
));

return $routes;
