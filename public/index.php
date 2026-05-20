<?php

declare(strict_types=1);

use Spoudazon\InkwellCms\Runtime\AppKernel;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;

require __DIR__ . '/../vendor/autoload.php';

AppKernel::bootstrap(AppRuntimeConfig::fromServer($_SERVER))->run();
