<?php
$content = file_get_contents('routes/web.php');

$append = <<<PHP

// Bulk routes
\$app->post('/admin/users/bulk', [\App\Controllers\Admin\UserController::class, 'bulk']);
\$app->post('/admin/posts/bulk', [\App\Controllers\Admin\PostController::class, 'bulk']);
\$app->post('/admin/pages/bulk', [\App\Controllers\Admin\PageController::class, 'bulk']);
\$app->post('/admin/media/bulk', [\App\Controllers\Admin\MediaController::class, 'bulk']);
\$app->post('/admin/comments/bulk', [\App\Controllers\Admin\CommentController::class, 'bulk']);

// Revision routes
\$app->get('/admin/posts/{id}/revisions', [\App\Controllers\Admin\RevisionController::class, 'index']);
\$app->get('/admin/pages/{id}/revisions', [\App\Controllers\Admin\RevisionController::class, 'index']);
\$app->get('/admin/revisions/{id}', [\App\Controllers\Admin\RevisionController::class, 'show']);
\$app->post('/admin/revisions/{id}/restore', [\App\Controllers\Admin\RevisionController::class, 'restore']);

// Autosave routes
\$app->post('/admin/posts/{id}/autosave', [\App\Controllers\Admin\PostController::class, 'autosave']);
\$app->post('/admin/pages/{id}/autosave', [\App\Controllers\Admin\PageController::class, 'autosave']);
PHP;

if (!str_contains($content, 'autosave')) {
    file_put_contents('routes/web.php', $content . $append);
}
