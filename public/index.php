<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../src/bootstrap.php';

$appRunner = new \Spoudazon\InkwellCms\Runtime\AppRunner($container);
$appRunner->run();
