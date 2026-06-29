<?php

declare(strict_types=1);

namespace App\Auth;

class AuthManager
{
    private static ?SessionGuard $guard = null;

    public static function guard(): SessionGuard
    {
        if (self::$guard === null) {
            self::$guard = new SessionGuard();
        }

        return self::$guard;
    }
}
