<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Runtime;

use InvalidArgumentException;
use Spoudazon\InkwellCms\Runtime\AppEnvironment;
use PHPUnit\Framework\TestCase;

final class AppEnvironmentTest extends TestCase
{
    public function testDefaultsToDevelopmentWhenEnvIsMissing(): void
    {
        $environment = AppEnvironment::fromServer([]);

        self::assertSame(AppEnvironment::Development, $environment);
    }

    public function testParsesAliases(): void
    {
        self::assertSame(AppEnvironment::Development, AppEnvironment::fromString('dev'));
        self::assertSame(AppEnvironment::Development, AppEnvironment::fromString('development'));
        self::assertSame(AppEnvironment::Test, AppEnvironment::fromString('test'));
        self::assertSame(AppEnvironment::Test, AppEnvironment::fromString('testing'));
        self::assertSame(AppEnvironment::Production, AppEnvironment::fromString('prod'));
        self::assertSame(AppEnvironment::Production, AppEnvironment::fromString('production'));
        self::assertTrue(AppEnvironment::fromString('production')->isProduction());
    }

    public function testThrowsForUnsupportedValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AppEnvironment::fromString('demo');
    }
}
