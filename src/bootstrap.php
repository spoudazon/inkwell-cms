<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Spoudazon\InkwellCms\Runtime\AppEnvironment;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;

$runtimeConfig = AppRuntimeConfig::fromServer($_SERVER);

$builder = new ContainerBuilder()
    ->useAutowiring(true)
    ->addDefinitions([
        AppRuntimeConfig::class => $runtimeConfig,
        AppEnvironment::class => $runtimeConfig->environment(),
    ])
    ->addDefinitions(__DIR__ . '/../config/services.php');

if ($runtimeConfig->isProduction()) {
    $builder
        ->enableCompilation($runtimeConfig->getCacheDir() . '/di')
        ->writeProxiesToFile(true, $runtimeConfig->getCacheDir() . '/proxies');
}

return $builder->build();
