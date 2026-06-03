<?php

declare(strict_types=1);

use Spoudazon\InkwellCms\Runtime\AppKernel;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

$config = AppRuntimeConfig::fromServer($_SERVER);

if ($config->isDebug()) {
    Debug::enable();
}

AppKernel::bootstrap($config)->run();
