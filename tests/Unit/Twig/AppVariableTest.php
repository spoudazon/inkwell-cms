<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Twig;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class AppVariableTest extends TestCase
{
    public function testExposesTheCurrentRequest(): void
    {
        $request = Request::create('/post/hello');

        self::assertSame($request, $this->appFor($request)->getRequest());
    }

    public function testReadsTheRouteNameFromTheCurrentRequest(): void
    {
        $request = Request::create('/post/hello');
        $request->attributes->set('_route', 'post');

        self::assertSame('post', $this->appFor($request)->getCurrentRoute());
    }

    public function testReadsTheRouteParametersFromTheCurrentRequest(): void
    {
        $request = Request::create('/post/hello');
        $request->attributes->set('_route_params', ['slug' => 'hello']);

        self::assertSame(['slug' => 'hello'], $this->appFor($request)->getCurrentRouteParameters());
    }

    public function testFallsBackToEmptyValuesWhenNoRequestIsActive(): void
    {
        // An empty stack mirrors rendering outside a request (e.g. from the CLI).
        $app = new AppVariable(new RequestStack());

        self::assertNull($app->getRequest());
        self::assertNull($app->getCurrentRoute());
        self::assertSame([], $app->getCurrentRouteParameters());
    }

    private function appFor(Request $request): AppVariable
    {
        $stack = new RequestStack();
        $stack->push($request);

        return new AppVariable($stack);
    }
}
