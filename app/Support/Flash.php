<?php

declare(strict_types=1);

namespace App\Support;

class Flash
{
    public static function set(string $key, mixed $value): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }

        return $default;
    }

    public static function has(string $key): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['flash'][$key]);
    }
}
