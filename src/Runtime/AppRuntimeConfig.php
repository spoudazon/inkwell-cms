<?php

declare(strict_types=1);

namespace Spoudazon\InkwellCms\Runtime;

use InvalidArgumentException;

final readonly class AppRuntimeConfig
{
    public function __construct(
        private AppEnvironment $environment,
        private bool $debug
    ) {
    }

    /**
     * @param array<string, mixed> $server
     */
    public static function fromServer(array $server): self
    {
        $environment = AppEnvironment::fromServer($server);
        $debug = self::resolveDebug($server['APP_DEBUG'] ?? null, $environment);

        return new self($environment, $debug);
    }

    public function environment(): AppEnvironment
    {
        return $this->environment;
    }

    public function isProduction(): bool
    {
        return $this->environment->isProduction();
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/var/cache/%s', dirname(__DIR__, 2), $this->environment->value);
    }

    private static function resolveDebug(mixed $rawValue, AppEnvironment $environment): bool
    {
        if ($rawValue === null || (is_string($rawValue) && trim($rawValue) === '')) {
            return self::defaultDebugFor($environment);
        }

        if (is_bool($rawValue)) {
            return $rawValue;
        }

        if (is_int($rawValue)) {
            return match ($rawValue) {
                0 => false,
                1 => true,
                default => throw self::invalidDebugValue($rawValue),
            };
        }

        if (!is_string($rawValue)) {
            throw self::invalidDebugValue($rawValue);
        }

        return match (strtolower(trim($rawValue))) {
            '1', 'true', 'on', 'yes' => true,
            '0', 'false', 'off', 'no' => false,
            default => throw self::invalidDebugValue($rawValue),
        };
    }

    private static function defaultDebugFor(AppEnvironment $environment): bool
    {
        return match ($environment) {
            AppEnvironment::Production => false,
            AppEnvironment::Development, AppEnvironment::Test => true,
        };
    }

    private static function invalidDebugValue(mixed $rawValue): InvalidArgumentException
    {
        return new InvalidArgumentException(sprintf(
            'Unsupported APP_DEBUG value "%s".',
            is_scalar($rawValue) ? (string)$rawValue : get_debug_type($rawValue)
        ));
    }
}
