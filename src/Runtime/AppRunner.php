<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Runtime;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;

final readonly class AppRunner
{
    public function __construct(
        private Container $container
    ) {
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        $kernel = $this->container->get(HttpKernel::class);
        assert($kernel instanceof HttpKernel, 'Expected HttpKernel instance from container.');
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
}
