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
        self::assertStringContainsString('<h1>Content Stored as Markdown Files</h1>', $html);
        self::assertStringContainsString('<h2>File format</h2>', $html);
    }

    public function testPostRouteReturns404ForUnknownSlug(): void
    {
        $response = $this->request('GET', '/post/no-such-post');

        self::assertSame(404, $response->getStatusCode());
    }
}
