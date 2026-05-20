<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Runtime;

use InvalidArgumentException;
use Spoudazon\InkwellCms\Runtime\AppEnvironment;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use PHPUnit\Framework\TestCase;

final class AppRuntimeConfigTest extends TestCase
{
    public function testDebugDefaultsToTrueForDevelopment(): void
    {
        $config = AppRuntimeConfig::fromServer(['APP_ENV' => 'dev']);

        self::assertTrue($config->isDebug());
    }

    public function testDebugDefaultsToFalseForProduction(): void
    {
        $config = AppRuntimeConfig::fromServer(['APP_ENV' => 'prod']);

        self::assertFalse($config->isDebug());
    }

    public function testProductionCacheDir(): void
    {
        $config = AppRuntimeConfig::fromServer(['APP_ENV' => 'prod']);
        $projectRoot = dirname(__DIR__, 3);

        self::assertSame($projectRoot . '/var/cache/production', $config->getCacheDir());
    }

    public function testDebugCanBeExplicitlyEnabledForProduction(): void
    {
        $config = AppRuntimeConfig::fromServer([
            'APP_ENV' => 'prod',
            'APP_DEBUG' => '1',
        ]);

        self::assertSame(AppEnvironment::Production, $config->environment());
        self::assertTrue($config->isDebug());
    }

    public function testDebugCanBeExplicitlyDisabledForDevelopment(): void
    {
        $config = AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => 'off',
        ]);

        self::assertFalse($config->isDebug());
    }

    public function testDebugIsBool(): void
    {
        $config = AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => false,
        ]);

        self::assertFalse($config->isDebug());
    }

    public function testDebugIsIntFalse(): void
    {
        $config = AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => 0,
        ]);

        self::assertFalse($config->isDebug());
    }

    public function testDebugIsIntTrue(): void
    {
        $config = AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => 1,
        ]);

        self::assertTrue($config->isDebug());
    }

    public function testInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => [],
        ]);
    }

    public function testThrowsForUnsupportedDebugValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AppRuntimeConfig::fromServer([
            'APP_ENV' => 'dev',
            'APP_DEBUG' => 'maybe',
        ]);
    }
}
