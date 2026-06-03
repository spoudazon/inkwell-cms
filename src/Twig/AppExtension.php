<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly AppVariable $app,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getGlobals(): array
    {
        return ['app' => $this->app];
    }
}
