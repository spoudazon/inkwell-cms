#!/usr/bin/env php
<?php

declare(strict_types=1);

use Spoudazon\InkwellCms\Runtime\AppContainerFactory;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Spoudazon\InkwellCms\Service\ThemeAssetsPublisher;

require __DIR__ . '/../vendor/autoload.php';

$container = (new AppContainerFactory())->create(AppRuntimeConfig::fromServer($_SERVER));
$container->get(ThemeAssetsPublisher::class)->publishAssets();

fwrite(STDOUT, "Theme assets published." . PHP_EOL);
