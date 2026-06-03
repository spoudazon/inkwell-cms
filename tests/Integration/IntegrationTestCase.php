<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Runtime\AppContainerFactory;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Base for integration tests: boots the real application container once per
 * test. Use $this->container to reach wired services, or $this->request() to
 * drive the kernel through a full request/response cycle.
 */
abstract class IntegrationTestCase extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = (new AppContainerFactory())->create(
            AppRuntimeConfig::fromServer($_SERVER),
        );
    }

    protected function request(string $method, string $uri): Response
    {
        $kernel = $this->container->get(HttpKernel::class);
        assert($kernel instanceof HttpKernel);

        return $kernel->handle(Request::create($uri, $method));
    }
}
