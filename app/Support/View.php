<?php

declare(strict_types=1);

namespace App\Support;

use Intisari\Application;

class View
{
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public static function render(string $view, array $data = []): string
    {
        $app = Application::getGlobal();
        if ($app) {
            return $app->render($view, $data);
        }

        return '';
    }
}
