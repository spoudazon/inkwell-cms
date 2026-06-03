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
        // The <h1> comes from the page's title and the <li> from its raw HTML
        // body, so finding both proves the slug resolved and page.html.twig
        // rendered the page in full -- not just its heading.
        self::assertStringContainsString('<h1>About</h1>', $html);
        self::assertStringContainsString('<li>Notes on PHP', $html);
    }

    public function testPageRouteReturns404ForUnknownSlug(): void
    {
        // The /page/{slug} route matches any slug, so a 404 here is the
        // controller rejecting the page -- not the router failing to match.
        $response = $this->request('GET', '/page/no-such-page');

        self::assertSame(404, $response->getStatusCode());
    }
}
