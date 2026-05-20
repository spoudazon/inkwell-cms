<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Runtime;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

final readonly class AppKernel
{
    public function __construct(
        private HttpKernel $kernel
    ) {
    }

    public static function bootstrap(
        AppRuntimeConfig $config,
        ?AppContainerFactory $containerFactory = null
    ): self {
        $container = ($containerFactory ?? new AppContainerFactory())->create($config);
        $kernel = $container->get(HttpKernel::class);
        assert($kernel instanceof HttpKernel);

        return new self($kernel);
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        $response = $this->handle($request);
        $response->send();
        $this->kernel->terminate($request, $response);
    }

    public function handle(Request $request): Response
    {
        return $this->kernel->handle($request);
    }
}
