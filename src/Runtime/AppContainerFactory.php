<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Runtime;

use DI\Container;
use DI\ContainerBuilder;

final readonly class AppContainerFactory
{
    /**
     * @param ContainerBuilder<Container> $builder
     */
    public function __construct(
        private ContainerBuilder $builder = new ContainerBuilder()
    ) {
    }

    public function create(AppRuntimeConfig $config): Container
    {
        $this->builder
            ->useAutowiring(true)
            ->addDefinitions([
                AppRuntimeConfig::class => $config,
                AppEnvironment::class => $config->environment(),
            ])
            ->addDefinitions(dirname(__DIR__, 2) . '/config/services.php');

        if ($config->isProduction()) {
            $this->builder
                ->enableCompilation($config->getCacheDir() . '/di')
                ->writeProxiesToFile(true, $config->getCacheDir() . '/proxies');
        }

        return $this->builder->build();
    }
}
