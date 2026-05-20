<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Runtime;

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Runtime\AppContainerFactory;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;

final class AppContainerFactoryTest extends TestCase
{
    public function testProductionEnablesCompilationAndWritesProxiesToFile(): void
    {
        $builder = $this->createMock(ContainerBuilder::class);
        $builder->method('useAutowiring')->willReturnSelf();
        $builder->method('addDefinitions')->willReturnSelf();
        $builder->method('build')->willReturn(new Container());

        $builder->expects($this->once())
            ->method('enableCompilation')
            ->with($this->stringEndsWith('/var/cache/production/di'))
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('writeProxiesToFile')
            ->with(true, $this->stringEndsWith('/var/cache/production/proxies'))
            ->willReturnSelf();

        $factory = new AppContainerFactory($builder);
        $factory->create(AppRuntimeConfig::fromServer(['APP_ENV' => 'prod']));
    }

    public function testNonProductionSkipsCompilationAndProxies(): void
    {
        $builder = $this->createMock(ContainerBuilder::class);
        $builder->method('useAutowiring')->willReturnSelf();
        $builder->method('addDefinitions')->willReturnSelf();
        $builder->method('build')->willReturn(new Container());

        $builder->expects($this->never())->method('enableCompilation');
        $builder->expects($this->never())->method('writeProxiesToFile');

        $factory = new AppContainerFactory($builder);
        $factory->create(AppRuntimeConfig::fromServer(['APP_ENV' => 'dev']));
    }
}
