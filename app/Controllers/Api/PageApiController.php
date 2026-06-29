<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Http\JsonResponse;
use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;
use Lukman\Http\Request;

class PageApiController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    public function index(Request $request): JsonResponse
    {
        $page = (int)($_GET['page'] ?? 1);
        $paginator = $this->repo->paginate(PostType::PAGE, $page, 10, '', PostStatus::PUBLISHED);

        $data = array_map(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'published_at' => $post->published_at,
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
