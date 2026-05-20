<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\WebTestCase;

final class ErrorControllerTest extends WebTestCase
{
    public function testErrorHandling(): void
    {
        $response = $this->request('GET', '/nonexistent');

        self::assertSame(404, $response->getStatusCode());
        self::assertNotNull(
            $response->headers->get('X-Debug-Exception'),
            'Error has not passed through ErrorController/HtmlErrorRenderer.',
        );
    }
}
