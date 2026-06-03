<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Controller;

use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\HttpFoundation\Response;

final readonly class ErrorController
{
    public function __construct(
        private HtmlErrorRenderer $renderer,
    ) {
    }

    public function __invoke(\Throwable $exception): Response
    {
        $flattened = $this->renderer->render($exception);

        return new Response(
            $flattened->getAsString(),
            $flattened->getStatusCode(),
            $flattened->getHeaders(),
        );
    }
}
