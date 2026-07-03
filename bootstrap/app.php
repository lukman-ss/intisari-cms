<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Helpers/plugin_helpers.php';

use Intisari\Application;

$app = new Application(dirname(__DIR__));
$app->setAsGlobal();
$app->register(\App\Providers\AppServiceProvider::class);
$app->middleware([
    \App\Security\SecurityHeaders::class,
    \App\Security\CsrfGuard::class,
    \App\Security\SeoRedirectMiddleware::class,
]);

if (is_file($app->basePath('.env'))) {
    $app->loadEnvironment($app->basePath('.env'));
}

if (is_dir($app->configPath())) {
    $app->loadConfiguration($app->configPath());
}

return $app;
