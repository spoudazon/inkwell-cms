<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Twig;

use Spoudazon\InkwellCms\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class AppGlobalTest extends IntegrationTestCase
{
    public function testTheAppGlobalIsAvailableToTemplates(): void
    {
        // Rendering through `app` at all proves the global is registered: with
        // strict_variables on, an unknown `app` would throw instead.
        $rendered = $this->twig()->createTemplate(
            "{{ app.request is null ? 'no-request' : 'has-request' }}",
        )->render();

        self::assertSame('no-request', $rendered);
    }

    public function testTemplatesReadTheCurrentRouteThroughTheAppGlobal(): void
    {
        $stack = $this->container->get(RequestStack::class);
        self::assertInstanceOf(RequestStack::class, $stack);

        // Push a request onto the very stack the kernel feeds during a real
        // request, then render through the container's Twig: this proves the
        // `app` global reflects the live request end to end.
        $request = Request::create('/post/hello');
        $request->attributes->set('_route', 'post');
        $stack->push($request);

        $rendered = $this->twig()
            ->createTemplate('{{ app.currentRoute }}')
            ->render();

        self::assertSame('post', $rendered);
    }

    private function twig(): Environment
    {
        $twig = $this->container->get(Environment::class);
        self::assertInstanceOf(Environment::class, $twig);

        return $twig;
    }
}
