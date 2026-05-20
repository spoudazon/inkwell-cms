<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Runtime;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Runtime\AppKernel;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\HttpFoundation\Request;

final class AppKernelTest extends TestCase
{
    public function testHandleReturnsResponseFromRegisteredRoute(): void
    {
        $app = AppKernel::bootstrap(AppRuntimeConfig::fromServer($_SERVER));

        $response = $app->handle(Request::create('/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('Hello world!', $response->getContent());
    }

    public function testRunSendsResponseFromGlobalsToOutput(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        try {
            $app = AppKernel::bootstrap(AppRuntimeConfig::fromServer($_SERVER));

            ob_start();
            try {
                $app->run();
            } finally {
                $output = ob_get_clean();
            }

            self::assertSame('Hello world!', $output);
        } finally {
            $_SERVER = $originalServer;
        }
    }
}
