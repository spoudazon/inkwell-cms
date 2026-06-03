<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class PageController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(string $slug): Response
    {
        $pages = $this->pages();

        if (!isset($pages[$slug])) {
            throw new NotFoundHttpException(sprintf('No page exists for slug "%s".', $slug));
        }

        return new Response($this->twig->render('pages/page.html.twig', [
            'page' => $pages[$slug],
        ]));
    }

    /**
     * Hard-coded placeholder static pages, keyed by slug. A page is just a
     * title and an HTML body -- the shape a Markdown source file produces
     * once rendered. Add an entry here and it is served at /page/{slug}.
     * Replace with a real content source later.
     *
     * @return array<string, array{title: string, body: string}>
     */
    private function pages(): array
    {
        return [
            'about' => [
                'title' => 'About',
                'body' => <<<'HTML'
                    <p>I'm Alex Carter. I build small, sharp software and write about
                    the parts that don't fit in a commit message.</p>

                    <p>Inkwell began as the CMS running this site and quietly became
                    the subject of half its posts. When a tool annoys me enough, it
                    tends to end up here as an essay.</p>

                    <h2>What you'll find here</h2>

                    <ul>
                    <li>Notes on PHP, written for people who already know the language</li>
                    <li>Frontend craft — typography, theming, and turning down framework defaults</li>
                    <li>The occasional post-mortem of a decision that looked smart at the time</li>
                    </ul>

                    <p>Everything here is hand-written and deliberately short. Posts go
                    up when there is something worth saying, and not before.</p>
                    HTML,
            ],
            'contact' => [
                'title' => 'Contact',
                'body' => ''
            ],
        ];
    }
}
