<?php

declare(strict_types=1);

$env = function_exists('env') ? 'env' : function ($key, $default = null) {
    $val = getenv($key);
    return $val !== false ? $val : $default;
};

return [
    'default' => $env('DB_CONNECTION', 'sqlite'),
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => $env('DB_DATABASE', 'database/cms.sqlite'),
    ],
    'mysql' => [
        'driver' => 'mysql',
        'host' => $env('DB_HOST', '127.0.0.1'),
        'database' => $env('DB_DATABASE', 'forge'),
        'username' => $env('DB_USERNAME', 'forge'),
        'password' => $env('DB_PASSWORD', ''),
    ],
    'pgsql' => [
        'driver' => 'pgsql',
        'host' => $env('DB_HOST', '127.0.0.1'),
        'database' => $env('DB_DATABASE', 'forge'),
        'username' => $env('DB_USERNAME', 'forge'),
        'password' => $env('DB_PASSWORD', ''),
    ],
];
