<?php

declare(strict_types=1);

namespace App\Controllers;

use Intisari\Application;

abstract class BaseController
{
    protected Application $app;

    public function __construct()
    {
        $this->app = Application::getGlobal()
            ?? throw new \RuntimeException('No global application instance available.');
    }

    protected function appName(): string
    {
        return (string) $this->app->config()->get('app.name', 'Intisari CMS');
    }
}
