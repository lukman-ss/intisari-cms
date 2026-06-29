<?php

declare(strict_types=1);

namespace App\Support;

use Intisari\Application;

class Url
{
    public static function to(string $path): string
    {
        $app = Application::getGlobal();
        $baseUrl = $app ? $app->config()->get('app.url', 'http://localhost') : 'http://localhost';
        
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    public static function asset(string $path): string
    {
        return self::to($path);
    }
}
