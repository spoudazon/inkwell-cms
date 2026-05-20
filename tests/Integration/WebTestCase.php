<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Runtime\AppKernel;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends TestCase
{
    protected AppKernel $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = AppKernel::bootstrap(AppRuntimeConfig::fromServer($_SERVER));
    }

    protected function request(string $method, string $uri): Response
    {
        return $this->app->handle(Request::create($uri, $method));
    }
}
