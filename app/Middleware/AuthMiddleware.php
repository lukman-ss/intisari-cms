<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\AuthManager;
use App\Support\Redirect;
use Lukman\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!AuthManager::guard()->check()) {
            return Redirect::to('/admin/login');
        }

        return $next($request);
    }
}
