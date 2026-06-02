<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testHomeRouteRendersHomePage(): void
    {
        $response = $this->request('GET', '/');

        self::assertSame(200, $response->getStatusCode());
        // The post-list container is rendered only by the home page's content
        // block, so its presence proves routing, the controller and Twig all ran.
        self::assertStringContainsString(
            'class="post-list"',
            (string) $response->getContent(),
        );
    }

    public function testHomePageListsPosts(): void
    {
        $html = (string) $this->request('GET', '/')->getContent();

        // A post-card title only appears when the post loop ran over a
        // non-empty list -- an empty list renders the "No posts." branch
        // instead. Finding a title proves the controller supplied posts and
        // post-card.html.twig rendered them.
        self::assertStringContainsString(
            '<h2 class="title">Rendering Markdown Without the Ceremony</h2>',
            $html,
        );
    }

    public function testHomePageShowsSiteIntro(): void
    {
        $html = (string) $this->request('GET', '/')->getContent();

        // The intro section is rendered only when the controller passes a
        // non-empty `intro`, so the section class together with a fragment of
        // its text proves that value reached and rendered in the view.
        self::assertStringContainsString('class="blog-intro"', $html);
        self::assertStringContainsString('notebook on building small, sharp', $html);
    }
}
