<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Router;

use Spoudazon\InkwellCms\Tests\Integration\WebTestCase;

final class RouterHandleStatusesTest extends WebTestCase
{
    public function testHomeRouteReturnsSuccessfulResponse(): void
    {
        $response = $this->request('GET', '/');

        self::assertSame(200, $response->getStatusCode());
    }

    public function testNotFoundRouteReturns404(): void
    {
        $response = $this->request('GET', '/some-not-found-route');

        self::assertSame(404, $response->getStatusCode());
    }

    public function testMethodNotAllowedReturns405(): void
    {
        $response = $this->request('POST', '/');

        self::assertSame(405, $response->getStatusCode());
    }
}
