<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\IntegrationTestCase;

final class PostControllerTest extends IntegrationTestCase
{
    public function testPostRouteRendersPostPage(): void
    {
        $response = $this->request('GET', '/post/content-stored-as-markdown-files');

        self::assertSame(200, $response->getStatusCode());

        $html = (string) $response->getContent();
        // The <h1> is the post title and the <h2> comes from inside the post's
        // raw HTML body, so finding both proves the /post/{slug} route resolved
        // and post.html.twig rendered the post in full -- heading and body.
        self::assertStringContainsString('<h1>Content Stored as Markdown Files</h1>', $html);
        self::assertStringContainsString('<h2>File format</h2>', $html);
    }

    public function testPostRouteReturns404ForUnknownSlug(): void
    {
        // The /post/{slug} route matches any slug, so a 404 here is the
        // controller rejecting the post -- not the router failing to match.
        $response = $this->request('GET', '/post/no-such-post');

        self::assertSame(404, $response->getStatusCode());
    }
}
