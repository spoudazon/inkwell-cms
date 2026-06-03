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
            'your-content-stays-in-plain-markdown' => [
                'url' => '/post/your-content-stays-in-plain-markdown',
                'title' => 'Your Content Stays in Plain Markdown',
                'date' => '2026-05-14',
                'updated' => null,
                'read_time' => 6,
                'body' => <<<'HTML'
                    <p>Every page and post in Inkwell is a single Markdown file living in a
                    folder you own. There is no hidden table, no opaque export format, and
                    nothing proprietary standing between you and your words.</p>

                    <h2>Files you can actually hold</h2>

                    <p>Open the content folder and you will see exactly what you wrote:
                    plain text with a little front matter on top. Edit it in any editor,
                    rename it, or move it to another machine — it behaves like every other
                    file on your disk because that is all it is.</p>

                    <pre><code class="language-markdown">---
                    title: Your Content Stays in Plain Markdown
                    date: 2026-05-14
                    ---

                    Every page and post is just a Markdown file in a folder you own.</code></pre>

                    <h2>Git is your history</h2>

                    <p>Because the content is text, your version control already knows what
                    to do with it. Every edit is a diff you can read, every revision is a
                    commit you can revert, and your backup strategy is whatever you already
                    use for code.</p>

                    <ul>
                    <li>No database to dump before you can leave</li>
                    <li>No migration script when you change tools</li>
                    <li>No lock-in beyond a folder of files</li>
                    </ul>

                    <p>The format is the feature: keep your writing in plain Markdown and it
                    stays yours, long after any particular CMS has come and gone.</p>
                    HTML,
            ],
            'theme-it-with-twig-nothing-exotic' => [
                'url' => '/post/theme-it-with-twig-nothing-exotic',
                'title' => 'Theme It With Twig, Nothing Exotic',
                'date' => '2026-05-03',
                'updated' => null,
                'read_time' => 5,
                'body' => <<<'HTML'
                    <p>Inkwell renders every page through Twig, so a theme is nothing more
                    than templates and assets you can read at a glance. There is no bespoke
                    templating dialect to learn and no framework hiding the markup from you.</p>

                    <h2>Templates you can read</h2>

                    <p>A layout is a Twig file with the blocks you would expect. Override the
                    parts that matter to your design and inherit the rest, so a small theme
                    stays small instead of copying boilerplate it never touches.</p>

                    <pre><code class="language-twig">{% extends 'base.html.twig' %}

                    {% block content %}
                      <article>{{ post.body|raw }}</article>
                    {% endblock %}</code></pre>

                    <h2>Assets ship as-is</h2>

                    <p>CSS, fonts and images sit beside the templates and are published
                    untouched. Style what your readers see; leave the routing, caching and
                    request handling to the plumbing underneath.</p>

                    <ul>
                    <li>Edit the markup directly — it is just HTML in Twig</li>
                    <li>Drop in stylesheets and fonts without a build pipeline</li>
                    <li>Change the design without touching application code</li>
                    </ul>

                    <p>The goal is a theme that does one job well: deciding how the site
                    looks, and nothing more exotic than that.</p>
                    HTML,
            ],
            'fast-because-there-is-less-of-it' => [
                'url' => '/post/fast-because-there-is-less-of-it',
                'title' => 'Fast Because There Is Less of It',
                'date' => '2026-04-19',
                'updated' => null,
                'read_time' => 4,
                'body' => <<<'HTML'
                    <p>Speed in Inkwell is not a clever trick or an aggressive cache. It is
                    the natural result of doing less: no database round-trips and no sprawling
                    plugin stack, just files on disk rendered to HTML.</p>

                    <h2>Fewer moving parts</h2>

                    <p>Every layer you remove is a layer that cannot be slow. There is no query
                    to optimise because there is no query, and no plugin to profile because the
                    request path is short enough to keep in your head.</p>

                    <ul>
                    <li>Read a Markdown file</li>
                    <li>Render it through a Twig template</li>
                    <li>Send the HTML</li>
                    </ul>

                    <h2>Small enough to understand</h2>

                    <p>When the whole site fits in a folder, performance stops being a project
                    and becomes a side effect. Pages load quickly, the moving parts stay
                    countable, and you can reason about the entire system without a diagram.</p>

                    <p>The fastest code is the code you never wrote — Inkwell simply takes that
                    seriously.</p>
                    HTML,
            ],
            'up-and-running-in-a-few-minutes' => [
                'url' => '/post/up-and-running-in-a-few-minutes',
                'title' => 'Up and Running in a Few Minutes',
                'date' => '2026-04-06',
                'updated' => null,
                'read_time' => null,
                'body' => <<<'HTML'
                    <p>Getting started with Inkwell is deliberately unremarkable. Drop in your
                    Markdown, pick a theme, and point a web server at the folder. That is the
                    whole setup — the rest is just writing.</p>

                    <h2>Three steps</h2>

                    <ul>
                    <li>Add your <strong>Markdown</strong> files to the content folder</li>
                    <li>Choose a <strong>theme</strong> for how the site should look</li>
                    <li>Serve the folder with the <strong>web server</strong> you already have</li>
                    </ul>

                    <pre><code class="language-bash"># Drop a page in, then serve it
                    echo '# Hello, Inkwell' > content/hello.md
                    php -S localhost:8000 -t public</code></pre>

                    <h2>Then get out of the way</h2>

                    <p>There is no onboarding wizard, no account to create, and no dashboard to
                    configure before your first page is live. Once the folder is being served,
                    publishing is just saving a file.</p>

                    <p>Setup should be the least interesting part of running a site, so Inkwell
                    keeps it that way and lets you spend your time on the words.</p>
                    HTML,
            ],
            'a-theme-system-that-stays-out-of-the-way' => [
                'url' => '/post/a-theme-system-that-stays-out-of-the-way',
                'title' => 'A Theme System That Stays Out of the Way',
                'date' => '2026-05-14',
                'updated' => null,
                'read_time' => 7,
                'body' => <<<'HTML'
                    <p>Every theme starts with good intentions and ends as a tangle of
                    overrides. Inkwell takes the boring way out: give a theme three
                    clearly separated jobs and never let them bleed into one another.</p>

                    <h2>Three jobs, three folders</h2>

                    <p>A theme is just a directory. Templates render HTML, assets carry
                    whatever the browser downloads, and a small config file declares the
                    handful of values an author is actually allowed to change.</p>

                    <ul>
                    <li><strong>templates/</strong> — Twig files, and nothing but Twig files</li>
                    <li><strong>assets/</strong> — CSS, fonts and images, published as-is</li>
                    <li><strong>website config</strong> — the small surface an author edits</li>
                    </ul>

                    <p>Assets are mirrored into the public directory on first request and
                    then left alone. There is no build step to forget and no stale bundle
                    to chase down later:</p>

                    <pre><code class="language-twig">{% include 'partials/header.html.twig' %}
                    {% block content %}{% endblock %}
                    {% include 'partials/footer.html.twig' %}</code></pre>

                    <p>That single constraint is the whole design — everything else is just
                    keeping the three folders honest.</p>
                    HTML,
            ],
        ];
    }
}
