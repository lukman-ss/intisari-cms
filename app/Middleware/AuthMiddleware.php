<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use Lukman\Http\MiddlewareInterface;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

/**
 * Protect routes that require an authenticated user.
 * Unauthenticated requests are redirected to /admin/login.
 */
final class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!Auth::check()) {
            return new RedirectResponse('/admin/login');
        }

        return $handler->handle($request);
    }
}
