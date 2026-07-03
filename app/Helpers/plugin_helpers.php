<?php
declare(strict_types=1);

use App\Repositories\OptionRepository;
use App\Plugins\ShortcodeManager;
use App\Plugins\HookManager;

if (!function_exists('get_setting')) {
    function get_setting(string $key, mixed $default = null): mixed
    {
        $repo = new OptionRepository();
        return $repo->get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    function set_setting(string $key, mixed $value): bool
    {
        $repo = new OptionRepository();
        return $repo->set($key, $value);
    }
}

if (!function_exists('parse_shortcodes')) {
    function parse_shortcodes(string $content): string
    {
        return ShortcodeManager::parse($content);
    }
}

if (!function_exists('register_shortcode')) {
    function register_shortcode(string $tag, callable $callback): void
    {
        ShortcodeManager::add($tag, $callback);
    }
}

if (!function_exists('register_action')) {
    function register_action(string $tag, callable $callback, int $priority = 10): void
    {
        HookManager::addAction($tag, $callback, $priority);
    }
}

if (!function_exists('execute_action')) {
    function execute_action(string $tag, mixed ...$args): void
    {
        HookManager::doAction($tag, ...$args);
    }
}

if (!function_exists('register_filter')) {
    function register_filter(string $tag, callable $callback, int $priority = 10): void
    {
        HookManager::addFilter($tag, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return HookManager::applyFilters($tag, $value, ...$args);
    }
}
