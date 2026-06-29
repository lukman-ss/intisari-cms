<?php

declare(strict_types=1);

namespace App\Plugins;

class HookManager
{
    private static array $actions = [];
    private static array $filters = [];

    // Filters

    public static function addFilter(string $tag, callable $callback, int $priority = 10): void
    {
        if (!isset(self::$filters[$tag])) {
            self::$filters[$tag] = [];
        }
        self::$filters[$tag][$priority][] = $callback;
        ksort(self::$filters[$tag]);
    }

    public static function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        if (!isset(self::$filters[$tag])) {
            return $value;
        }

        foreach (self::$filters[$tag] as $priorityGroup) {
            foreach ($priorityGroup as $callback) {
                $value = call_user_func($callback, $value, ...$args);
            }
        }

        return $value;
    }

    // Actions

    public static function addAction(string $tag, callable $callback, int $priority = 10): void
    {
        if (!isset(self::$actions[$tag])) {
            self::$actions[$tag] = [];
        }
        self::$actions[$tag][$priority][] = $callback;
        ksort(self::$actions[$tag]);
    }

    public static function doAction(string $tag, mixed ...$args): void
    {
        if (!isset(self::$actions[$tag])) {
            return;
        }

        foreach (self::$actions[$tag] as $priorityGroup) {
            foreach ($priorityGroup as $callback) {
                call_user_func($callback, ...$args);
            }
        }
    }
}
