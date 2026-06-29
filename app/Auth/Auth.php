<?php

declare(strict_types=1);

namespace App\Auth;

use Intisari\Application;
use Lukman\Session\SessionStore;

/**
 * Simple session-based authentication helper.
 *
 * Usage: Auth::login($user), Auth::check(), Auth::user(), Auth::logout()
 */
final class Auth
{
    private const SESSION_KEY = '_auth.user_id';

    private static ?Application $app = null;

    public static function setApp(Application $app): void
    {
        self::$app = $app;
    }

    /**
     * Log a user in by storing their ID in the session.
     *
     * @param array<string, mixed> $user
     */
    public static function login(array $user): void
    {
        $session = self::session();
        $session->put(self::SESSION_KEY, (int) $user['id']);
    }

    /**
     * Determine whether a user is currently authenticated.
     */
    public static function check(): bool
    {
        try {
            $session = self::session();
            return $session->has(self::SESSION_KEY);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get the currently authenticated user's ID, or null.
     */
    public static function id(): ?int
    {
        try {
            $value = self::session()->get(self::SESSION_KEY);
            return $value !== null ? (int) $value : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Get the currently authenticated user record, or null.
     *
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        $id = self::id();
        if ($id === null) {
            return null;
        }

        $app = self::getApp();
        $connection = $app->db();
        $qb = new \Lukman\Database\QueryBuilder($connection);

        return $qb->table('users')->where('id', $id)->first();
    }

    /**
     * Log out the current user.
     */
    public static function logout(): void
    {
        try {
            $session = self::session();
            $session->forget(self::SESSION_KEY);
            $session->regenerate(destroyOld: true);
        } catch (\Throwable) {
            // Session not started — nothing to do.
        }
    }

    private static function session(): SessionStore
    {
        return self::getApp()->session();
    }

    private static function getApp(): Application
    {
        $app = self::$app ?? Application::getGlobal();

        if ($app === null) {
            throw new \RuntimeException('Auth: no Application instance available. Call Auth::setApp() first.');
        }

        return $app;
    }
}
