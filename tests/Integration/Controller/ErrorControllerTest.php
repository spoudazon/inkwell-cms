<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Integration\Controller;

use Spoudazon\InkwellCms\Tests\Integration\IntegrationTestCase;

final class ErrorControllerTest extends IntegrationTestCase
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
