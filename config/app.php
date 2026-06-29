<?php

declare(strict_types=1);

$env = static fn (string $key, mixed $default = null): mixed => $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;

return [
    'name'     => $env('APP_NAME', 'Intisari CMS'),
    'env'      => $env('APP_ENV', 'production'),
    'debug'    => (bool) $env('APP_DEBUG', false),
    'url'      => $env('APP_URL', 'http://localhost'),
    'key'      => $env('APP_KEY', ''),
    'timezone' => $env('APP_TIMEZONE', 'UTC'),
    'locale'   => $env('APP_LOCALE', 'en'),
    'providers' => [
        \App\Providers\AppServiceProvider::class,
    ],
    'middleware' => [],
];
