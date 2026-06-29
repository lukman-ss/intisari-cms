<?php

declare(strict_types=1);

namespace App\Providers;

use Intisari\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(CmsServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
