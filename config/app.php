<?php

declare(strict_types=1);

$env = function_exists('env') ? 'env' : function ($key, $default = null) {
    $val = getenv($key);
    return $val !== false ? $val : $default;
};

return [
    'name' => $env('APP_NAME', 'Intisari CMS'),
    'env' => $env('APP_ENV', 'production'),
    'debug' => (bool)$env('APP_DEBUG', false),
    'url' => $env('APP_URL', 'http://localhost'),
    'timezone' => $env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => $env('APP_LOCALE', 'en'),
    'providers' => [],
    'middleware' => [],
];
