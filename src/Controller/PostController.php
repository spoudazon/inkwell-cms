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
            'themes-are-twig-templates' => [
                'url' => '/post/themes-are-twig-templates',
                'title' => 'Themes Are Twig Templates',
                'date' => '2026-05-03',
                'updated' => null,
                'read_time' => 5,
                'body' => <<<'HTML'
                    <p>Pages are rendered through Twig. A theme is a set of Twig templates
                    together with the static assets the pages reference. There is no
                    separate template language and no build step.</p>

                    <h2>Templates</h2>

                    <p>A layout is a Twig file built from blocks. Templates extend a base
                    layout and override the blocks they need, so shared markup is defined
                    once rather than repeated.</p>

                    <pre><code class="language-twig">{% extends 'base.html.twig' %}

                    {% block content %}
                      <article>{{ post.body|raw }}</article>
                    {% endblock %}</code></pre>

                    <h2>Assets</h2>

                    <p>CSS, fonts and images live alongside the templates and are published
                    unchanged. Routing, caching and request handling stay in the application
                    rather than in the theme.</p>

                    <ul>
                    <li>Templates are HTML with Twig tags</li>
                    <li>Stylesheets and fonts are served as static files</li>
                    <li>The design can change without touching application code</li>
                    </ul>
                    HTML,
            ],
            'few-moving-parts' => [
                'url' => '/post/few-moving-parts',
                'title' => 'Few Moving Parts',
                'date' => '2026-04-19',
                'updated' => null,
                'read_time' => 4,
                'body' => <<<'HTML'
                    <p>Handling a request is short: read a Markdown file from disk and render
                    it through a Twig template. There is no database query and no plugin layer
                    in the path.</p>

                    <h2>Request path</h2>

                    <ul>
                    <li>Read the Markdown file for the requested URL</li>
                    <li>Render it through a Twig template</li>
                    <li>Return the resulting HTML</li>
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
                    <p>Inkwell serves a directory of Markdown files. Running it locally needs
                    PHP and a web server pointed at the public folder; PHP's built-in server
                    is enough for development.</p>

                    <h2>Steps</h2>

                    <ul>
                    <li>Add Markdown files to the content folder</li>
                    <li>Select a theme for rendering</li>
                    <li>Start a web server pointed at the public folder</li>
                    </ul>

                    <pre><code class="language-bash"># Add a page, then serve the site
                    echo '# Hello, Inkwell' > content/hello.md
                    php -S localhost:8000 -t public</code></pre>

                    <h2>Publishing</h2>

                    <p>There is no separate publishing step. Once the folder is being served,
                    a page becomes available as soon as its file is saved.</p>
                    HTML,
            ],
            'how-themes-are-structured' => [
                'url' => '/post/how-themes-are-structured',
                'title' => 'How Themes Are Structured',
                'date' => '2026-05-14',
                'updated' => null,
                'read_time' => 7,
                'body' => <<<'HTML'
                    <p>A theme is a directory with three separate responsibilities:
                    templates, assets and configuration. Keeping them apart makes the
                    structure predictable.</p>

                    <h2>Templates, assets and config</h2>

                    <p>Templates render HTML, assets carry whatever the browser downloads,
                    and a small config file declares the values a site is allowed to set.</p>

                    <ul>
                    <li><strong>templates/</strong> — Twig files</li>
                    <li><strong>assets/</strong> — CSS, fonts and images, published as-is</li>
                    <li><strong>website config</strong> — the values a site can change</li>
                    </ul>

                    <p>Assets are copied into the public directory on first request and then
                    left in place, so there is no separate build step:</p>

                    <pre><code class="language-twig">{% include 'partials/header.html.twig' %}
                    {% block content %}{% endblock %}
                    {% include 'partials/footer.html.twig' %}</code></pre>

                    <p>Each of the three parts has one job, which is the whole of the
                    structure.</p>
                    HTML,
            ],
        ];
    }
}
