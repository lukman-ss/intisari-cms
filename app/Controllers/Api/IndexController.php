<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Http\JsonResponse;
use Lukman\Http\Request;

class IndexController
{
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse([
            'name' => 'Intisari CMS API',
            'version' => '1.0.0',
            'routes' => [
                '/api/v1/posts',
                '/api/v1/pages',
                '/api/v1/media',
                '/api/v1/categories',
                '/api/v1/tags',
            ]
        ]);
    }
}
