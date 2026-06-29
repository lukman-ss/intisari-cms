<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Http\JsonResponse;
use App\Repositories\MediaRepository;
use Lukman\Http\Request;

class MediaApiController
{
    private MediaRepository $repo;

    public function __construct()
    {
        $this->repo = new MediaRepository();
    }

    public function index(Request $request): JsonResponse
    {
        $page = (int)($_GET['page'] ?? 1);
        $paginator = $this->repo->paginate($page, 10);

        $data = array_map(function($media) {
            return [
                'id' => $media->id,
                'filename' => $media->filename,
                'path' => $media->path,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'alt_text' => $media->alt_text,
                'url' => '/storage/' . ltrim($media->path, '/')
            ];
        }, $paginator['data']);

        return new JsonResponse([
            'data' => $data,
            'meta' => [
                'current_page' => $paginator['current_page'],
                'last_page' => $paginator['last_page'],
                'total' => $paginator['total'],
            ]
        ]);
    }
}
