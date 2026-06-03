<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Service;

use DI\Attribute\Inject;
use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

final readonly class ThemeAssetsPublisher
{
    private const string MANIFEST_FILE = 'theme-assets.manifest';
    private const string LOCK_FILE = 'theme-assets.lock';

    public function __construct(
        #[Inject('app.theme')]
        private string $theme,

        #[Inject('app.root')]
        private string $appRoot,

        #[Inject('app.cache_dir')]
        private string $cacheDir,

        #[Inject('app.public_assets_dir')]
        private string $publicAssetsDir,

        private AppRuntimeConfig $config,

        private Filesystem $filesystem = new Filesystem(),
    ) {
    }

    public function publishAssets(): void
    {
        $source = sprintf('%s/themes/%s/assets', $this->appRoot, $this->theme);

        if (!$this->filesystem->exists($source)) {
            return;
        }

        // Outside debug mode assets are immutable for the lifetime of a
        // deployment: publish once, then skip the directory scan entirely.
        if (!$this->config->isDebug() && $this->isPublished()) {
            return;
        }

        $this->withLock(function () use ($source): void {
            $this->filesystem->mirror(
                $source,
                $this->appRoot . '/public' . $this->publicAssetsDir,
                options: ['override' => false, 'delete' => true],
            );

            $this->filesystem->dumpFile($this->manifestPath(), $this->theme);
        });
    }

    private function isPublished(): bool
    {
        $manifest = $this->manifestPath();

        if (!is_file($manifest)) {
            return false;
        }

        return trim((string)file_get_contents($manifest)) === $this->theme;
    }

    private function withLock(callable $publish): void
    {
        $lockPath = $this->cacheDir . '/' . self::LOCK_FILE;
        $this->filesystem->mkdir($this->cacheDir);

        // @ silences fopen's warning; the false return is handled explicitly.
        $handle = @fopen($lockPath, 'c');
        if ($handle === false) {
            throw new IOException(
                sprintf('Unable to open the theme assets lock file "%s".', $lockPath),
                path: $lockPath,
            );
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new IOException(
                    sprintf('Unable to acquire exclusive lock on the theme assets lock file "%s".', $lockPath),
                    path: $lockPath,
                );
            }
            $publish();
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function manifestPath(): string
    {
        return $this->cacheDir . '/' . self::MANIFEST_FILE;
    }
}
