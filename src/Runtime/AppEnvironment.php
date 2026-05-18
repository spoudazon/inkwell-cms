<?php

declare(strict_types=1);

namespace MzStack\InkwellCms\Runtime;

use InvalidArgumentException;

enum AppEnvironment: string
{
    case Production = 'production';
    case Development = 'development';
    case Test = 'test';

    /**
     * @param array<string, mixed> $server
     */
    public static function fromServer(array $server): self
    {
        $rawValue = $server['APP_ENV'] ?? null;
        if (!is_string($rawValue) || trim($rawValue) === '') {
            return self::Development;
        }

        return self::fromString($rawValue);
    }

    public static function fromString(string $rawValue): self
    {
        return match (strtolower(trim($rawValue))) {
            'prod', 'production' => self::Production,
            'dev', 'development' => self::Development,
            'test', 'testing' => self::Test,
            default => throw new InvalidArgumentException(sprintf(
                'Unsupported APP_ENV value "%s".',
                $rawValue
            )),
        };
    }

    public function isProduction(): bool
    {
        return $this === self::Production;
    }
}
