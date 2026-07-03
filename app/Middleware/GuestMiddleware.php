<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\AuthManager;
use App\Support\Redirect;
use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

class GuestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): string|Response
    {
        if (AuthManager::guard()->check()) {
            return Redirect::to('/admin/dashboard');
        }

        return $next($request);
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $response = $this->handle($request, fn (Request $request) => $handler->handle($request));
        return $response instanceof Response ? $response : new Response((string)$response);
    }
}
