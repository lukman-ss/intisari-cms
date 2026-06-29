<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';

$app->loadRoutes(dirname(__DIR__) . '/routes/web.php');

$app->run();
