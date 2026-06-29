<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\Csrf;
use Lukman\Http\Request;
use Lukman\Http\Response;

class CsrfGuard
{
    public function handle(Request $request, \Closure $next): string|Response
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        
        // Skip CSRF for API
        if (str_starts_with($uri, '/api/')) {
            return $next($request);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!Csrf::validateToken($token)) {
                http_response_code(419);
                return 'Page expired. Please go back and try again.';
            }
        }

        return $next($request);
    }
}
