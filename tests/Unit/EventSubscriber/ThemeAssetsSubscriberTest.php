<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\EventSubscriber\ThemeAssetsSubscriber;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Spoudazon\InkwellCms\Service\ThemeAssetsPublisher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ThemeAssetsSubscriberTest extends TestCase
{
    private string $root;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
        $this->root = sys_get_temp_dir() . '/' . uniqid('inkwell-subscriber-test-', true);
        $this->filesystem->dumpFile($this->root . '/themes/default/assets/css/all.css', 'body{}');
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->root);

        parent::tearDown();
    }

    public function testPublishesOnTheMainRequestInDebug(): void
    {
        $this->subscriber('dev')->onKernelRequest($this->mainRequest());

        self::assertFileExists($this->root . '/public/assets/css/all.css');
    }

    public function testSkipsOutsideDebug(): void
    {
        $this->subscriber('prod')->onKernelRequest($this->mainRequest());

        self::assertDirectoryDoesNotExist($this->root . '/public/assets');
    }

    public function testSkipsSubRequestsEvenInDebug(): void
    {
        $event = new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            Request::create('/'),
            HttpKernelInterface::SUB_REQUEST,
        );

        $this->subscriber('dev')->onKernelRequest($event);

        self::assertDirectoryDoesNotExist($this->root . '/public/assets');
    }

    private function subscriber(string $env): ThemeAssetsSubscriber
    {
        $publisher = new ThemeAssetsPublisher(
            theme: 'default',
            appRoot: $this->root,
            publicAssetsDir: '/assets',
            filesystem: $this->filesystem,
        );

        return new ThemeAssetsSubscriber(
            $publisher,
            AppRuntimeConfig::fromServer(['APP_ENV' => $env]),
        );
    }

    private function mainRequest(): RequestEvent
    {
        return new RequestEvent(
            $this->createStub(HttpKernelInterface::class),
            Request::create('/'),
            HttpKernelInterface::MAIN_REQUEST,
        );
    }
}
