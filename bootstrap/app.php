<?php

declare(strict_types=1);

use Intisari\Application;

$app = new Application(dirname(__DIR__));
$app->setAsGlobal();
$app->register(\App\Providers\AppServiceProvider::class);
$app->middleware([
    \App\Security\SecurityHeaders::class,
    \App\Security\CsrfGuard::class,
]);

if (is_file($app->basePath('.env'))) {
    $app->loadEnvironment($app->basePath('.env'));
}

if (is_dir($app->configPath())) {
    $app->loadConfiguration($app->configPath());
}

return $app;
