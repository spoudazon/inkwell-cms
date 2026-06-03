<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class AppVariable
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getCurrentRoute(): ?string
    {
        $route = $this->getRequest()?->attributes->get('_route');

        return is_string($route) ? $route : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCurrentRouteParameters(): array
    {
        $parameters = $this->getRequest()?->attributes->get('_route_params');

        return is_array($parameters) ? $parameters : [];
    }
}
