<?php

declare(strict_types=1);

namespace App\Security;

use Lukman\Http\MiddlewareInterface;
use Lukman\Http\Request;
use Lukman\Http\RequestHandlerInterface;
use Lukman\Http\Response;

class SecurityHeaders implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): string|Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $response->headers()->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers()->set('X-XSS-Protection', '1; mode=block');
            $response->headers()->set('X-Content-Type-Options', 'nosniff');
            $response->headers()->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers()->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");
        } else {
            if (!headers_sent()) {
                header('X-Frame-Options: SAMEORIGIN');
                header('X-XSS-Protection: 1; mode=block');
                header('X-Content-Type-Options: nosniff');
                header('Referrer-Policy: strict-origin-when-cross-origin');
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");
            }
        }

        return $response;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $response = $this->handle($request, fn (Request $request) => $handler->handle($request));
        return $response instanceof Response ? $response : new Response((string)$response);
    }
}
