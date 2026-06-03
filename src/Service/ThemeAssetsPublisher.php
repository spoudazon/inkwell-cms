<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Service;

use DI\Attribute\Inject;
use Symfony\Component\Filesystem\Filesystem;

final readonly class ThemeAssetsPublisher
{
    public function __construct(
        #[Inject('app.theme')] private string $theme,
        #[Inject('app.root')] private string $appRoot,
        #[Inject('app.public_assets_dir')] private string $publicAssetsDir,
        private Filesystem $filesystem = new Filesystem(),
    ) {
    }

    public function publishAssets(): void
    {
        $source = sprintf('%s/themes/%s/assets', $this->appRoot, $this->theme);

        if (!$this->filesystem->exists($source)) {
            return;
        }

        // Idempotent full sync: override keeps published copies in step with the
        // source regardless of mtime, delete drops files no longer in the theme.
        $this->filesystem->mirror(
            $source,
            $this->appRoot . '/public' . $this->publicAssetsDir,
            options: ['override' => true, 'delete' => true],
        );
    }
}
