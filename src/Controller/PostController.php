<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class PostController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(string $slug): Response
    {
        $posts = $this->posts();

        if (!isset($posts[$slug])) {
            throw new NotFoundHttpException(sprintf('No post exists for slug "%s".', $slug));
        }

        return new Response($this->twig->render('pages/post.html.twig', [
            'post' => $posts[$slug],
            'author' => ['name' => 'Alex Carter'],
            'prev' => null,
            'next' => null,
        ]));
    }

    /**
     * Hard-coded placeholder posts, keyed by slug. A post is the shape a
     * Markdown source file produces once rendered: metadata plus a `body` of
     * ready HTML -- not a structured block tree. Add an entry here and it is
     * served at /post/{slug}; an unknown slug 404s. Replace with a real
     * content source later.
     *
     * @return array<string, array{
     *     url: string,
     *     title: string,
     *     date: string,
     *     updated: string|null,
     *     read_time: int|null,
     *     body: string
     * }>
     */
    private function posts(): array
    {
        return [
            'content-stored-as-markdown-files' => [
                'url' => '/post/content-stored-as-markdown-files',
                'title' => 'Content Stored as Markdown Files',
                'date' => '2026-05-14',
                'updated' => null,
                'read_time' => 6,
                'body' => <<<'HTML'
                    <p>Each page and post is a single Markdown file in a content folder.
                    There is no database and no separate storage format: the file on disk
                    is the source of the page.</p>

                    <h2>File format</h2>

                    <p>A file is plain text with a small block of front matter at the top
                    for metadata, followed by the Markdown body. The files can be created
                    and edited with any text editor.</p>

                    <pre><code class="language-markdown">---
                    title: Content Stored as Markdown Files
                    date: 2026-05-14
                    ---

                    Each page and post is a single Markdown file in a content folder.</code></pre>

                    <h2>Version control</h2>

                    <p>Because the content is text, it can be tracked in Git like source
                    code. Changes appear as readable diffs, revisions can be reverted, and
                    backups are copies of the folder.</p>

                    <ul>
                    <li>No database to export or import</li>
                    <li>No migration step when moving content</li>
                    <li>The content folder can be copied between machines as-is</li>
                    </ul>
                    HTML,
            ],
            'writing-posts-in-markdown' => [
                'url' => '/post/writing-posts-in-markdown',
                'title' => 'Writing Posts in Markdown',
                'date' => '2026-05-03',
                'updated' => null,
                'read_time' => 5,
                'body' => <<<'HTML'
                    <p>A post is a Markdown file. The top of the file holds a small block of
                    front matter for metadata; everything below it is the text of the post,
                    written in ordinary Markdown.</p>

                    <h2>Front matter</h2>

                    <p>The front matter is a short list of values, such as the title and the
                    date. These are read when the page is built and used for things like the
                    heading and the post listing.</p>

                    <pre><code class="language-markdown">---
                    title: Writing Posts in Markdown
                    date: 2026-05-03
                    ---

                    The text of the post starts here.</code></pre>

                    <h2>Formatting</h2>

                    <p>The body uses standard Markdown, so the common elements all work without
                    anything extra:</p>

                    <ul>
                    <li>Headings, paragraphs and lists</li>
                    <li>Links, emphasis and inline code</li>
                    <li>Fenced code blocks</li>
                    </ul>

                    <p>When the page is built, the Markdown is turned into the HTML that is
                    sent to the browser.</p>
                    HTML,
            ],
            'few-moving-parts' => [
                'url' => '/post/few-moving-parts',
                'title' => 'Few Moving Parts',
                'date' => '2026-04-19',
                'updated' => null,
                'read_time' => 4,
                'body' => <<<'HTML'
                    <p>Handling a request is short: read a Markdown file from disk and turn it
                    into an HTML page. There is no database query and no plugin layer in the
                    path.</p>

                    <h2>Request path</h2>

                    <ul>
                    <li>Read the Markdown file for the requested URL</li>
                    <li>Convert the Markdown to HTML</li>
                    <li>Return the resulting page</li>
                    </ul>

                    <h2>Consequences</h2>

                    <p>With fewer components there is less to configure and less to inspect
                    when something goes wrong. The whole site is a folder of files, which
                    keeps the system small enough to read end to end.</p>
                    HTML,
            ],
            'running-inkwell-locally' => [
                'url' => '/post/running-inkwell-locally',
                'title' => 'Running Inkwell Locally',
                'date' => '2026-04-06',
                'updated' => null,
                'read_time' => null,
                'body' => <<<'HTML'
                    <p>Inkwell comes with a Docker setup, so it can be started without
                    installing PHP or a web server on the host. A single command builds the
                    image and serves the site.</p>

                    <h2>Starting it</h2>

                    <pre><code class="language-bash"># Build the image and serve the site
                    docker compose up</code></pre>

                    <p>Once it is running, the site is available at
                    <code>http://localhost:8080</code>. The content folder is mounted into the
                    container, so edits to the Markdown files show up without a rebuild.</p>

                    <h2>Publishing</h2>

                    <p>There is no separate publishing step. While the site is being served, a
                    page becomes available as soon as its file is saved.</p>
                    HTML,
            ],
        ];
    }
}
