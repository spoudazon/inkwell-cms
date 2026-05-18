<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testHomeRouteReturnsSuccessfulResponse(): void
    {
        $response = $this->request('GET', '/');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('Hello world!', $response->getContent());
    }
}
