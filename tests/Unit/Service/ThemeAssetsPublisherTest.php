<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Spoudazon\InkwellCms\Service\ThemeAssetsPublisher;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

final class ThemeAssetsPublisherTest extends TestCase
{
    private string $root;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
        $this->root = sys_get_temp_dir() . '/' . uniqid('inkwell-assets-test-', true);
        $this->filesystem->mkdir($this->root);
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->root);

        parent::tearDown();
    }

    public function testPublishesThemeAssetsIntoPublicDirectory(): void
    {
        self::assertFileDoesNotExist($this->publishedAssetPath('css/all.css'));

        $this->giveThemeAsset('default', 'css/all.css', 'body{}');

        $this->publisher()->publishAssets();

        self::assertStringEqualsFile($this->publishedAssetPath('css/all.css'), 'body{}');
    }

    public function testWritesManifestNamingThePublishedTheme(): void
    {
        $assetFile = $this->root . '/var/cache/theme-assets.manifest';

        self::assertFileDoesNotExist($assetFile);

        $this->giveThemeAsset('default', 'css/all.css', 'body{}');

        $this->publisher(theme: 'default')->publishAssets();

        self::assertStringEqualsFile($assetFile, 'default');
    }

    public function testDoesNothingWhenThemeShipsNoAssets(): void
    {
        // No themes/default/assets directory is created at all.
        $this->publisher()->publishAssets();

        self::assertDirectoryDoesNotExist($this->root . '/public/assets');
    }

    public function testDebugModeRepublishesNewlyAddedFiles(): void
    {
        $this->giveThemeAsset('default', 'css/all.css', 'body{}');
        $publisher = $this->publisher(env: 'dev');
        $publisher->publishAssets();

        $this->giveThemeAsset('default', 'css/print.css', '@media print{}');
        $publisher->publishAssets();

        self::assertFileExists($this->publishedAssetPath('css/print.css'));
    }

    public function testDebugModeRepublishesUpdatedFileContents(): void
    {
        $source = $this->giveThemeAsset('default', 'css/all.css', 'old');
        $publisher = $this->publisher(env: 'dev');
        $publisher->publishAssets();

        $this->giveThemeAsset('default', 'css/all.css', 'new');
        // mirror() copies a file only when the source is newer than the
        // published copy; bump the mtime explicitly so the assertion does
        // not depend on sub-second timing between the two publish calls.
        touch($source, time() + 5);
        $publisher->publishAssets();

        self::assertStringEqualsFile($this->publishedAssetPath('css/all.css'), 'new');
    }

    public function testRemovesAssetsThatNoLongerExistInTheTheme(): void
    {
        $stale = $this->giveThemeAsset('default', 'css/legacy.css', 'legacy');
        $publisher = $this->publisher(env: 'dev');
        $publisher->publishAssets();
        self::assertFileExists($this->publishedAssetPath('css/legacy.css'));

        $this->filesystem->remove($stale);
        $publisher->publishAssets();

        self::assertFileDoesNotExist($this->publishedAssetPath('css/legacy.css'));
    }

    public function testProductionPublishesOnlyOnce(): void
    {
        $this->giveThemeAsset('default', 'css/all.css', 'body{}');
        $publisher = $this->publisher(env: 'prod');
        $publisher->publishAssets();

        // A file added after the first publish must be ignored in production.
        $this->giveThemeAsset('default', 'css/print.css', '@media print{}');
        $publisher->publishAssets();

        self::assertFileDoesNotExist($this->publishedAssetPath('css/print.css'));
    }

    public function testProductionRepublishesWhenTheConfiguredThemeChanges(): void
    {
        $this->giveThemeAsset('default', 'css/light.css', 'light');
        $this->publisher(theme: 'default', env: 'prod')->publishAssets();

        $this->giveThemeAsset('dark', 'css/dark.css', 'dark');
        $this->publisher(theme: 'dark', env: 'prod')->publishAssets();

        self::assertFileExists($this->publishedAssetPath('css/dark.css'));
        self::assertFileDoesNotExist($this->publishedAssetPath('css/light.css'));
    }

    public function testThrowsWhenTheLockFileCannotBeOpened(): void
    {
        $this->giveThemeAsset('default', 'css/all.css', 'body{}');
        // Occupy the lock file path with a directory so fopen() cannot open
        // it — deterministic regardless of the user the test runs as.
        $this->filesystem->mkdir($this->root . '/var/cache/theme-assets.lock');

        $this->expectException(IOException::class);

        $this->publisher()->publishAssets();
    }

    private function publisher(string $theme = 'default', string $env = 'dev'): ThemeAssetsPublisher
    {
        return new ThemeAssetsPublisher(
            theme: $theme,
            appRoot: $this->root,
            cacheDir: $this->root . '/var/cache',
            publicAssetsDir: '/assets',
            config: AppRuntimeConfig::fromServer(['APP_ENV' => $env]),
            filesystem: $this->filesystem,
        );
    }

    private function giveThemeAsset(string $theme, string $relativePath, string $contents): string
    {
        $path = sprintf('%s/themes/%s/assets/%s', $this->root, $theme, $relativePath);
        $this->filesystem->dumpFile($path, $contents);

        return $path;
    }

    private function publishedAssetPath(string $relativePath): string
    {
        return $this->root . '/public/assets/' . $relativePath;
    }
}
