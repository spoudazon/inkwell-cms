<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Spoudazon\InkwellCms\Service\ThemeAssetsPublisher;
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

    public function testDoesNothingWhenThemeShipsNoAssets(): void
    {
        // No themes/default/assets directory is created at all.
        $this->publisher()->publishAssets();

        self::assertDirectoryDoesNotExist($this->root . '/public/assets');
    }

    public function testRepublishesNewlyAddedFiles(): void
    {
        $this->giveThemeAsset('default', 'css/all.css', 'body{}');
        $publisher = $this->publisher();
        $publisher->publishAssets();

        $this->giveThemeAsset('default', 'css/print.css', '@media print{}');
        $publisher->publishAssets();

        self::assertFileExists($this->publishedAssetPath('css/print.css'));
    }

    public function testRepublishesUpdatedFileContents(): void
    {
        $this->giveThemeAsset('default', 'css/all.css', 'old');
        $publisher = $this->publisher();
        $publisher->publishAssets();

        $this->giveThemeAsset('default', 'css/all.css', 'new');
        $publisher->publishAssets();

        self::assertStringEqualsFile($this->publishedAssetPath('css/all.css'), 'new');
    }

    public function testRemovesAssetsThatNoLongerExistInTheTheme(): void
    {
        $stale = $this->giveThemeAsset('default', 'css/legacy.css', 'legacy');
        $publisher = $this->publisher();
        $publisher->publishAssets();
        self::assertFileExists($this->publishedAssetPath('css/legacy.css'));

        $this->filesystem->remove($stale);
        $publisher->publishAssets();

        self::assertFileDoesNotExist($this->publishedAssetPath('css/legacy.css'));
    }

    public function testRepublishesWhenTheConfiguredThemeChanges(): void
    {
        $this->giveThemeAsset('default', 'css/light.css', 'light');
        $this->publisher(theme: 'default')->publishAssets();

        $this->giveThemeAsset('dark', 'css/dark.css', 'dark');
        $this->publisher(theme: 'dark')->publishAssets();

        self::assertFileExists($this->publishedAssetPath('css/dark.css'));
        self::assertFileDoesNotExist($this->publishedAssetPath('css/light.css'));
    }

    private function publisher(string $theme = 'default'): ThemeAssetsPublisher
    {
        return new ThemeAssetsPublisher(
            theme: $theme,
            appRoot: $this->root,
            publicAssetsDir: '/assets',
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
