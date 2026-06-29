<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\ApiTokenManager;
use App\Http\JsonResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ApiAuthMiddleware
{
    public function handle(Request $request, \Closure $next): string|Response
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        $token = substr($authHeader, 7);
        
        $manager = new ApiTokenManager();
        $userId = $manager->authenticate($token);
        
        if (!$userId) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        \App\Auth\AuthManager::guard()->loginUsingId($userId);
        
        return $next($request);
    }
}
