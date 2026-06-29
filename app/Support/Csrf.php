<?php

declare(strict_types=1);

namespace App\Support;

class Csrf
{
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validateToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function field(): string
    {
        $token = self::generateToken();
        $escaped = View::escape($token);
        return '<input type="hidden" name="_token" value="' . $escaped . '">';
    }
}
