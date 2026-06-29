<?php

declare(strict_types=1);

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\MediaController;
use App\Controllers\Admin\PageController;
use App\Controllers\Admin\PostController;
use App\Controllers\Admin\SettingController;
use App\Controllers\Admin\UserController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Lukman\Http\RedirectResponse;

// Root redirect to admin login
$app->get('/', static fn () => new RedirectResponse('/admin/login'));

// Auth routes (guest only)
$app->group(['prefix' => '/admin', 'middleware' => [GuestMiddleware::class]], function ($router): void {
    $router->get('/login', [LoginController::class, 'showForm']);
    $router->post('/login', [LoginController::class, 'login']);
});

// Logout (authenticated)
$app->group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class]], function ($router): void {
    $router->post('/logout', [LogoutController::class, 'logout']);
});

// Admin area (authenticated)
$app->group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class]], function ($router): void {

    // Dashboard
    $router->get('/dashboard', [DashboardController::class, 'index']);

    // Users
    $router->get('/users',             [UserController::class, 'index']);
    $router->get('/users/create',      [UserController::class, 'create']);
    $router->post('/users',            [UserController::class, 'store']);
    $router->get('/users/{id}',        [UserController::class, 'edit']);
    $router->post('/users/{id}',       [UserController::class, 'update']);
    $router->post('/users/{id}/delete', [UserController::class, 'destroy']);

    // Pages
    $router->get('/pages',              [PageController::class, 'index']);
    $router->get('/pages/create',       [PageController::class, 'create']);
    $router->post('/pages',             [PageController::class, 'store']);
    $router->get('/pages/{id}',         [PageController::class, 'edit']);
    $router->post('/pages/{id}',        [PageController::class, 'update']);
    $router->post('/pages/{id}/delete', [PageController::class, 'destroy']);

    // Posts
    $router->get('/posts',              [PostController::class, 'index']);
    $router->get('/posts/create',       [PostController::class, 'create']);
    $router->post('/posts',             [PostController::class, 'store']);
    $router->get('/posts/{id}',         [PostController::class, 'edit']);
    $router->post('/posts/{id}',        [PostController::class, 'update']);
    $router->post('/posts/{id}/delete', [PostController::class, 'destroy']);

    // Media
    $router->get('/media',          [MediaController::class, 'index']);
    $router->post('/media',         [MediaController::class, 'store']);
    $router->post('/media/{id}/delete', [MediaController::class, 'destroy']);

    // Settings
    $router->get('/settings',  [SettingController::class, 'index']);
    $router->post('/settings', [SettingController::class, 'update']);
});
