<?php

declare(strict_types=1);

use Intisari\Application;
use App\Providers\AppServiceProvider;

$app = new Application(dirname(__DIR__));

$app->setAsGlobal();

if (is_file($app->basePath('.env'))) {
    $app->loadEnvironment($app->basePath('.env'));
}

$app->loadConfiguration();

$app->register(AppServiceProvider::class);

return $app;
