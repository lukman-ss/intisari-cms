<?php
$webPath = 'routes/web.php';
$content = file_get_contents($webPath);

$inserts = [
    '/admin/users' => "\n\$app->post('/admin/users/bulk', [\App\Controllers\Admin\UserController::class, 'bulk']);",
    '/admin/posts' => "\n\$app->post('/admin/posts/bulk', [\App\Controllers\Admin\PostController::class, 'bulk']);",
    '/admin/pages' => "\n\$app->post('/admin/pages/bulk', [\App\Controllers\Admin\PageController::class, 'bulk']);",
    '/admin/media' => "\n\$app->post('/admin/media/bulk', [\App\Controllers\Admin\MediaController::class, 'bulk']);",
    '/admin/comments' => "\n\$app->post('/admin/comments/bulk', [\App\Controllers\Admin\CommentController::class, 'bulk']);",
];

foreach ($inserts as $route => $bulkRoute) {
    if (!str_contains($content, "post('{$route}/bulk'")) {
        $search = "\\\$app->get('{$route}', [\\App\\Controllers\\Admin\\";
        $content = preg_replace("/(\\$app->get\('".str_replace('/', '\/', $route)."', \[[^]]+\]\);)/", "$1$bulkRoute", $content);
    }
}

if (!str_contains($content, 'revisions')) {
    $revRoutes = <<<PHP

\$app->get('/admin/posts/{id}/revisions', [\App\Controllers\Admin\RevisionController::class, 'index']);
\$app->get('/admin/pages/{id}/revisions', [\App\Controllers\Admin\RevisionController::class, 'index']);
\$app->get('/admin/revisions/{id}', [\App\Controllers\Admin\RevisionController::class, 'show']);
\$app->post('/admin/revisions/{id}/restore', [\App\Controllers\Admin\RevisionController::class, 'restore']);
PHP;

    $content = preg_replace("/(\\$app->post\('\/admin\/posts\/\{id\}\/restore'.*\);)/", "$1\n$revRoutes", $content);
}

file_put_contents($webPath, $content);
