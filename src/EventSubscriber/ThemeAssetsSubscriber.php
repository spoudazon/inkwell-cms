<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\EventSubscriber;

use Spoudazon\InkwellCms\Runtime\AppRuntimeConfig;
use Spoudazon\InkwellCms\Service\ThemeAssetsPublisher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class ThemeAssetsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ThemeAssetsPublisher $publisher,
        private AppRuntimeConfig $config,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 512]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->config->isDebug()) {
            return;
        }

        $this->publisher->publishAssets();
    }
}
