<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\IntegrationTestCase;

final class PageControllerTest extends IntegrationTestCase
{
    public function testPageRouteRendersKnownPage(): void
    {
        $response = $this->request('GET', '/page/about');

        self::assertSame(200, $response->getStatusCode());

        $html = (string) $response->getContent();
        self::assertStringContainsString('<h1>About</h1>', $html);
        self::assertStringContainsString('<li>Notes on PHP', $html);
    }

    public function testPageRouteReturns404ForUnknownSlug(): void
    {
        $response = $this->request('GET', '/page/no-such-page');

        self::assertSame(404, $response->getStatusCode());
    }
}
