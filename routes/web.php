<?php

declare(strict_types=1);

/** @var \Intisari\Application $app */

$app->get('/', [\App\Routing\ContentRouter::class, 'handle']);
$app->get('/posts', [\App\Controllers\PostController::class, 'index']);

$app->get('/api/v1', [\App\Controllers\Api\IndexController::class, 'index']);
$app->get('/api/v1/posts', [\App\Controllers\Api\PostApiController::class, 'index']);
$app->get('/api/v1/posts/{id}', [\App\Controllers\Api\PostApiController::class, 'show']);
$app->get('/api/v1/pages', [\App\Controllers\Api\PageApiController::class, 'index']);
$app->get('/api/v1/media', [\App\Controllers\Api\MediaApiController::class, 'index']);
$app->get('/api/v1/categories', [\App\Controllers\Api\TermApiController::class, 'categories']);
$app->get('/api/v1/tags', [\App\Controllers\Api\TermApiController::class, 'tags']);

$app->get('/health', function () {
    return ['status' => 'OK'];
});

$app->get('/admin/login', [\App\Controllers\Auth\LoginController::class, 'show']);
$app->post('/admin/login', [\App\Controllers\Auth\LoginController::class, 'authenticate']);
$app->post('/admin/logout', [\App\Controllers\Auth\LogoutController::class, 'logout']);
$app->get('/logout', [\App\Controllers\Auth\LogoutController::class, 'logout']);

$app->get('/admin/dashboard', function () {
    if (!\App\Auth\AuthManager::guard()->check()) {
        return \App\Support\Redirect::to('/admin/login');
    }
    return app()->render('layouts/admin', ['title' => 'Dashboard', 'content' => app()->render('admin/dashboard')]);
});

$app->get('/install', [\App\Controllers\InstallController::class, 'index']);
$app->post('/install', [\App\Controllers\InstallController::class, 'store']);
$app->get('/install/done', [\App\Controllers\InstallController::class, 'done']);

$app->get('/admin/users', [\App\Controllers\Admin\UserController::class, 'index']);
$app->get('/admin/users/create', [\App\Controllers\Admin\UserController::class, 'create']);
$app->post('/admin/users', [\App\Controllers\Admin\UserController::class, 'store']);
$app->get('/admin/users/{id}/edit', [\App\Controllers\Admin\UserController::class, 'edit']);
$app->post('/admin/users/{id}', [\App\Controllers\Admin\UserController::class, 'update']);
$app->post('/admin/users/{id}/delete', [\App\Controllers\Admin\UserController::class, 'destroy']);

$app->get('/admin/roles', [\App\Controllers\Admin\RoleController::class, 'index']);
$app->get('/admin/roles/{id}/edit', [\App\Controllers\Admin\RoleController::class, 'edit']);
$app->post('/admin/roles/{id}', [\App\Controllers\Admin\RoleController::class, 'update']);

$app->get('/admin/posts', [\App\Controllers\Admin\PostController::class, 'index']);
$app->get('/admin/posts/create', [\App\Controllers\Admin\PostController::class, 'create']);
$app->post('/admin/posts', [\App\Controllers\Admin\PostController::class, 'store']);
$app->get('/admin/posts/{id}/edit', [\App\Controllers\Admin\PostController::class, 'edit']);
$app->post('/admin/posts/{id}', [\App\Controllers\Admin\PostController::class, 'update']);
$app->post('/admin/posts/{id}/delete', [\App\Controllers\Admin\PostController::class, 'destroy']);
$app->post('/admin/posts/{id}/trash', [\App\Controllers\Admin\PostController::class, 'trash']);
$app->post('/admin/posts/{id}/restore', [\App\Controllers\Admin\PostController::class, 'restore']);

$app->get('/admin/pages', [\App\Controllers\Admin\PageController::class, 'index']);
$app->get('/admin/pages/create', [\App\Controllers\Admin\PageController::class, 'create']);
$app->post('/admin/pages', [\App\Controllers\Admin\PageController::class, 'store']);
$app->get('/admin/pages/{id}/edit', [\App\Controllers\Admin\PageController::class, 'edit']);
$app->post('/admin/pages/{id}', [\App\Controllers\Admin\PageController::class, 'update']);
$app->post('/admin/pages/{id}/delete', [\App\Controllers\Admin\PageController::class, 'destroy']);
$app->post('/admin/pages/{id}/trash', [\App\Controllers\Admin\PageController::class, 'trash']);
$app->post('/admin/pages/{id}/restore', [\App\Controllers\Admin\PageController::class, 'restore']);

$app->get('/admin/categories', [\App\Controllers\Admin\CategoryController::class, 'index']);
$app->post('/admin/categories', [\App\Controllers\Admin\CategoryController::class, 'store']);
$app->get('/admin/categories/{id}/edit', [\App\Controllers\Admin\CategoryController::class, 'edit']);
$app->post('/admin/categories/{id}', [\App\Controllers\Admin\CategoryController::class, 'update']);
$app->post('/admin/categories/{id}/delete', [\App\Controllers\Admin\CategoryController::class, 'destroy']);

$app->get('/admin/tags', [\App\Controllers\Admin\TagController::class, 'index']);
$app->post('/admin/tags', [\App\Controllers\Admin\TagController::class, 'store']);
$app->get('/admin/tags/{id}/edit', [\App\Controllers\Admin\TagController::class, 'edit']);
$app->post('/admin/tags/{id}', [\App\Controllers\Admin\TagController::class, 'update']);
$app->post('/admin/tags/{id}/delete', [\App\Controllers\Admin\TagController::class, 'destroy']);

$app->get('/admin/media', [\App\Controllers\Admin\MediaController::class, 'index']);
$app->get('/admin/media/upload', [\App\Controllers\Admin\MediaController::class, 'upload']);
$app->post('/admin/media', [\App\Controllers\Admin\MediaController::class, 'store']);
$app->get('/admin/media/{id}/edit', [\App\Controllers\Admin\MediaController::class, 'edit']);
$app->post('/admin/media/{id}', [\App\Controllers\Admin\MediaController::class, 'update']);
$app->post('/admin/media/{id}/delete', [\App\Controllers\Admin\MediaController::class, 'destroy']);

$app->get('/admin/comments', [\App\Controllers\Admin\CommentController::class, 'index']);
$app->post('/admin/comments/{id}/approve', [\App\Controllers\Admin\CommentController::class, 'approve']);
$app->post('/admin/comments/{id}/spam', [\App\Controllers\Admin\CommentController::class, 'spam']);
$app->post('/admin/comments/{id}/trash', [\App\Controllers\Admin\CommentController::class, 'trash']);
$app->post('/admin/comments/{id}/delete', [\App\Controllers\Admin\CommentController::class, 'destroy']);
$app->post('/comments', [\App\Controllers\CommentController::class, 'store']);

$app->get('/admin/settings/general', [\App\Controllers\Admin\SettingController::class, 'general']);
$app->post('/admin/settings/general', [\App\Controllers\Admin\SettingController::class, 'updateGeneral']);
$app->get('/admin/settings/reading', [\App\Controllers\Admin\SettingController::class, 'reading']);
$app->post('/admin/settings/reading', [\App\Controllers\Admin\SettingController::class, 'updateReading']);
$app->get('/admin/settings/discussion', [\App\Controllers\Admin\SettingController::class, 'discussion']);
$app->post('/admin/settings/discussion', [\App\Controllers\Admin\SettingController::class, 'updateDiscussion']);
$app->get('/admin/settings/media', [\App\Controllers\Admin\SettingController::class, 'media']);
$app->post('/admin/settings/media', [\App\Controllers\Admin\SettingController::class, 'updateMedia']);
$app->get('/admin/settings/permalinks', [\App\Controllers\Admin\SettingController::class, 'permalinks']);
$app->post('/admin/settings/permalinks', [\App\Controllers\Admin\SettingController::class, 'updatePermalinks']);

$app->get('/admin/appearance/menus', [\App\Controllers\Admin\MenuController::class, 'index']);
$app->post('/admin/appearance/menus', [\App\Controllers\Admin\MenuController::class, 'store']);
$app->get('/admin/appearance/menus/{id}', [\App\Controllers\Admin\MenuController::class, 'edit']);
$app->post('/admin/appearance/menus/{id}', [\App\Controllers\Admin\MenuController::class, 'update']);
$app->post('/admin/appearance/menus/{id}/items', [\App\Controllers\Admin\MenuController::class, 'storeItem']);
$app->post('/admin/appearance/menus/{id}/items/{itemId}/delete', [\App\Controllers\Admin\MenuController::class, 'destroyItem']);

$app->get('/admin/appearance/themes', [\App\Controllers\Admin\ThemeController::class, 'index']);
$app->post('/admin/appearance/themes/{theme}/activate', [\App\Controllers\Admin\ThemeController::class, 'activate']);

$app->get('/admin/plugins', [\App\Controllers\Admin\PluginController::class, 'index']);
$app->post('/admin/plugins/{plugin}/activate', [\App\Controllers\Admin\PluginController::class, 'activate']);
$app->post('/admin/plugins/{plugin}/deactivate', [\App\Controllers\Admin\PluginController::class, 'deactivate']);

$app->get('/admin/tools/api-tokens', [\App\Controllers\Admin\ApiTokenController::class, 'index']);
$app->post('/admin/tools/api-tokens', [\App\Controllers\Admin\ApiTokenController::class, 'store']);
$app->post('/admin/tools/api-tokens/{id}/revoke', [\App\Controllers\Admin\ApiTokenController::class, 'revoke']);

$app->get('/admin/appearance/widgets', [\App\Controllers\Admin\WidgetController::class, 'index']);
$app->post('/admin/appearance/widgets', [\App\Controllers\Admin\WidgetController::class, 'store']);

$app->get('/{p1}', [\App\Routing\ContentRouter::class, 'handle']);
$app->get('/{p1}/{p2}', [\App\Routing\ContentRouter::class, 'handle']);
$app->get('/{p1}/{p2}/{p3}', [\App\Routing\ContentRouter::class, 'handle']);