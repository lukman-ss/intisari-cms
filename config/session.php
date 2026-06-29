<?php

declare(strict_types=1);

$env = function_exists('env') ? 'env' : function ($key, $default = null) {
    $val = getenv($key);
    return $val !== false ? $val : $default;
};

return [
    'driver' => $env('SESSION_DRIVER', 'file'),
    'lifetime' => (int)$env('SESSION_LIFETIME', 7200),
    'cookie' => $env('SESSION_COOKIE', 'intisari_cms_session'),
    'path' => '/',
    'secure' => (bool)$env('SESSION_SECURE', false),
    'http_only' => true,
    'same_site' => 'lax',
];
