<?php
declare(strict_types=1);

namespace App\Security;

use App\Repositories\RedirectRepository;
use Lukman\Http\Request;
use Lukman\Http\Response;

class SeoRedirectMiddleware
{
    public function handle(Request $request, callable $next): Response
    {
        // Don't intercept admin or api routes if we don't want to, or just intercept all
        $path = $request->path();

        if (!str_starts_with($path, '/admin') && !str_starts_with($path, '/api')) {
            $repo = new RedirectRepository();
            $redirect = $repo->findBySource($path);
            
            if ($redirect) {
                // Increment hits
                $repo->incrementHits($redirect->id);
                
                // Perform redirect
                $response = new Response('', $redirect->type);
                $response->header('Location', $redirect->target_url);
                return $response;
            }
        }

        return $next($request);
    }
}
