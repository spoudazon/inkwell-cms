<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\IntegrationTestCase;

final class HomeControllerTest extends IntegrationTestCase
{
    public function testHomeRouteRendersHomePage(): void
    {
        $response = $this->request('GET', '/');

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString(
            'class="post-list"',
            (string) $response->getContent(),
        );
    }

    public function testHomePageListsPosts(): void
    {
        $html = (string) $this->request('GET', '/')->getContent();

        self::assertStringContainsString(
            '<h2 class="title">Writing Posts in Markdown</h2>',
            $html,
        );
    }

    public function testHomePageShowsSiteIntro(): void
    {
        $html = (string) $this->request('GET', '/')->getContent();

        self::assertStringContainsString('class="blog-intro"', $html);
        self::assertStringContainsString('turns a folder of Markdown into', $html);
    }

    public function testCurrentRequestMarksTheActiveMenuItem(): void
    {
        $html = (string) $this->request('GET', '/')->getContent();

        $markup = (string) preg_replace('/\s+/', ' ', $html);

        self::assertStringContainsString('<a class="plain current" href="/">Home</a>', $markup);
    }
}
