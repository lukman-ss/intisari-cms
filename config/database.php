<?php

declare(strict_types=1);

$env = static fn (string $key, mixed $default = null): mixed => $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;

return [
    'default' => $env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => $env('DB_DATABASE', 'database/cms.sqlite'),
        ],
        'mysql' => [
            'driver'   => 'mysql',
            'host'     => $env('DB_HOST', '127.0.0.1'),
            'port'     => $env('DB_PORT', '3306'),
            'database' => $env('DB_DATABASE', 'intisari_cms'),
            'username' => $env('DB_USERNAME', 'root'),
            'password' => $env('DB_PASSWORD', ''),
        ],
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => $env('DB_HOST', '127.0.0.1'),
            'port'     => $env('DB_PORT', '5432'),
            'database' => $env('DB_DATABASE', 'intisari_cms'),
            'username' => $env('DB_USERNAME', 'postgres'),
            'password' => $env('DB_PASSWORD', ''),
        ],
    ],
];
