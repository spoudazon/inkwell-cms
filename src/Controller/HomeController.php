<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Controller;

use Symfony\Component\HttpFoundation\Response;

final class HomeController
{
    public function __invoke(): Response
    {
        return new Response('Hello world!');
    }
}
