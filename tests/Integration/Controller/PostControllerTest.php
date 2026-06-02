<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\WebTestCase;

final class PostControllerTest extends WebTestCase
{
    public function testPostRouteRendersPostPage(): void
    {
        $response = $this->request('GET', '/post/a-theme-system-that-stays-out-of-the-way');

        self::assertSame(200, $response->getStatusCode());

        $html = (string) $response->getContent();
        // The <h1> is the post title and the <h2> comes from inside the post's
        // raw HTML body, so finding both proves the /post/{slug} route resolved
        // and post.html.twig rendered the post in full -- heading and body.
        self::assertStringContainsString('<h1>A Theme System That Stays Out of the Way</h1>', $html);
        self::assertStringContainsString('<h2>Three jobs, three folders</h2>', $html);
    }

    public function testPostRouteReturns404ForUnknownSlug(): void
    {
        // The /post/{slug} route matches any slug, so a 404 here is the
        // controller rejecting the post -- not the router failing to match.
        $response = $this->request('GET', '/post/no-such-post');

        self::assertSame(404, $response->getStatusCode());
    }
}
