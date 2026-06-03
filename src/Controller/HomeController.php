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
        return "Inkwell is a flat-file CMS. It turns a folder of Markdown into "
            . "a plain HTML website using a handful of Twig templates. Content "
            . "lives in files on disk; there is no database and no admin panel.";
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
                'url' => '/post/content-stored-as-markdown-files',
                'title' => 'Content Stored as Markdown Files',
                'excerpt' => 'Each page and post is a Markdown file in a content folder. The '
                    . 'files can be edited in any text editor and tracked in version control. '
                    . 'No database is involved.',
                'date' => '2026-05-14',
                'read_time' => 6,
            ],
            [
                'url' => '/post/themes-are-twig-templates',
                'title' => 'Themes Are Twig Templates',
                'excerpt' => 'Pages are rendered through Twig. A theme is a set of templates '
                    . 'and static assets. There is no separate template language and no build '
                    . 'step.',
                'date' => '2026-05-03',
                'read_time' => 5,
            ],
            [
                'url' => '/post/few-moving-parts',
                'title' => 'Few Moving Parts',
                'excerpt' => 'A request reads a Markdown file and renders it through a Twig '
                    . 'template. With no database and no plugin layer, little stands between '
                    . 'the request and the HTML response.',
                'date' => '2026-04-19',
                'read_time' => 4,
            ],
            [
                'url' => '/post/running-inkwell-locally',
                'title' => 'Running Inkwell Locally',
                'excerpt' => 'Inkwell serves a directory of Markdown files. Local development '
                    . 'needs only PHP\'s built-in web server pointed at the public folder.',
                'date' => '2026-04-06',
                'read_time' => null,
            ],
        ];
    }
}
