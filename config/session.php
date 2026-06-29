<?php

declare(strict_types=1);

$env = static fn (string $key, mixed $default = null): mixed => $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;

return [
    'driver'   => $env('SESSION_DRIVER', 'file'),
    'lifetime' => (int) $env('SESSION_LIFETIME', 7200),
    'path'     => 'storage/sessions',
];
