<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class HomeController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(): Response
    {
        return new Response($this->twig->render('pages/home.html.twig', [
            'intro' => $this->siteIntro(),
            'posts' => $this->samplePosts(),
        ]));
    }

    private function siteIntro(): string
    {
        return "Inkwell is a flat-file CMS that turns a folder of Markdown into "
            . "a fast, tidy website — no database, no admin panel, nothing to "
            . "lock you in. Write in plain text, theme it with Twig, and "
            . "publish whenever you are ready.";
    }

    /**
     * @return list<array{
     *     url: string,
     *     title: string,
     *     excerpt: string,
     *     date: string,
     *     read_time: int|null
     * }>
     */
    private function samplePosts(): array
    {
        return [
            [
                'url' => '/post/your-content-stays-in-plain-markdown',
                'title' => 'Your Content Stays in Plain Markdown',
                'excerpt' => 'Every page and post is just a Markdown file in a folder you '
                    . 'own. Edit it in any editor, keep it in Git, and move it anywhere — '
                    . 'there is no database to export and nothing proprietary to escape.',
                'date' => '2026-05-14',
                'read_time' => 6,
            ],
            [
                'url' => '/post/theme-it-with-twig-nothing-exotic',
                'title' => 'Theme It With Twig, Nothing Exotic',
                'excerpt' => 'Inkwell renders through Twig, so a theme is just templates and '
                    . 'assets you can read at a glance. Change everything that matters to '
                    . 'your design and leave the plumbing untouched.',
                'date' => '2026-05-03',
                'read_time' => 5,
            ],
            [
                'url' => '/post/fast-because-there-is-less-of-it',
                'title' => 'Fast Because There Is Less of It',
                'excerpt' => 'No database round-trips, no sprawling plugin stack — just files '
                    . 'on disk rendered to HTML. Pages load quickly and the whole site stays '
                    . 'small enough to understand.',
                'date' => '2026-04-19',
                'read_time' => 4,
            ],
            [
                'url' => '/post/up-and-running-in-a-few-minutes',
                'title' => 'Up and Running in a Few Minutes',
                'excerpt' => 'Drop in your Markdown, pick a theme, point a web server at the '
                    . 'folder. That is the whole setup — the rest is just writing.',
                'date' => '2026-04-06',
                'read_time' => null,
            ],
        ];
    }
}
