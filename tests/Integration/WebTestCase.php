<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

abstract class WebTestCase extends TestCase
{
    protected Container $container;
    protected HttpKernel $kernel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = require __DIR__ . '/../../src/bootstrap.php';
        $kernel = $this->container->get(HttpKernel::class);
        assert($kernel instanceof HttpKernel, 'Expected HttpKernel instance from container.');
        $this->kernel = $kernel;
    }

    protected function request(string $method, string $uri): Response
    {
        $request = Request::create($uri, $method);
        $response = $this->kernel->handle($request);
        $this->kernel->terminate($request, $response);

        return $response;
    }
}
