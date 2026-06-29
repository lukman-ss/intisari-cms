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
 * Redirect authenticated users away from guest-only routes (e.g. login page).
 */
final class GuestMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (Auth::check()) {
            return new RedirectResponse('/admin/dashboard');
        }

        return $handler->handle($request);
    }
}
