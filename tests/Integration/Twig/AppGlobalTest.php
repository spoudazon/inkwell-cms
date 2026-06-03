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
        $rendered = $this->twig()->createTemplate(
            "{{ app.request is null ? 'no-request' : 'has-request' }}",
        )->render();

        self::assertSame('no-request', $rendered);
    }

    public function testTemplatesReadTheCurrentRouteThroughTheAppGlobal(): void
    {
        $stack = $this->container->get(RequestStack::class);
        self::assertInstanceOf(RequestStack::class, $stack);

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
