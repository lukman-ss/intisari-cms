<?php

declare(strict_types=1);

namespace App\Support;

use Lukman\Http\Response;

class Redirect
{
    public static function to(string $url, int $status = 302): Response
    {
        $response = new Response();
        $response->setStatusCode($status);
        $response->headers->set('Location', $url);

        return $response;
    }

    public static function back(string $fallback = '/'): Response
    {
        $url = $_SERVER['HTTP_REFERER'] ?? $fallback;
        return self::to($url);
    }
}
