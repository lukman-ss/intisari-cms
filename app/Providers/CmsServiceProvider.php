<?php

declare(strict_types=1);

namespace App\Providers;

use Intisari\ServiceProvider;

final class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Register the CMS view path so templates resolve correctly.
        $this->app->view()->finder()->addPath(
            $this->app->basePath('resources/views')
        );

        // Pre-share a null authUser; controllers override this at runtime.
        $this->app->view()->share('authUser', null);
    }
}
