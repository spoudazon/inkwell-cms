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
            . "a plain HTML website. Content lives in files on disk; there is no "
            . "database and no admin panel.";
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
                'url' => '/post/writing-posts-in-markdown',
                'title' => 'Writing Posts in Markdown',
                'excerpt' => 'A post is written in Markdown: a title and date in the front '
                    . 'matter, then the text. Standard formatting — headings, lists, links '
                    . 'and code — becomes the page.',
                'date' => '2026-05-03',
                'read_time' => 5,
            ],
            [
                'url' => '/post/few-moving-parts',
                'title' => 'Few Moving Parts',
                'excerpt' => 'A request reads a Markdown file and turns it into an HTML page. '
                    . 'With no database and no plugin layer, little stands between the request '
                    . 'and the response.',
                'date' => '2026-04-19',
                'read_time' => 4,
            ],
            [
                'url' => '/post/running-inkwell-locally',
                'title' => 'Running Inkwell Locally',
                'excerpt' => 'Inkwell comes with a Docker setup, so a single command builds the '
                    . 'image and serves the site locally. No separate environment needs to be '
                    . 'configured.',
                'date' => '2026-04-06',
                'read_time' => null,
            ],
        ];
    }
}
