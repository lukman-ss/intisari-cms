<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Http\JsonResponse;
use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;
use Lukman\Http\Request;

class PostApiController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    public function index(Request $request): JsonResponse
    {
        $page = (int)($_GET['page'] ?? 1);
        $paginator = $this->repo->paginate(PostType::POST, $page, 10, '', PostStatus::PUBLISHED);

        $data = array_map(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
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

    public function show(Request $request, string $id): JsonResponse
    {
        $post = $this->repo->findById((int)$id);
        if (!$post || $post->status !== PostStatus::PUBLISHED || $post->type !== PostType::POST) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        return new JsonResponse([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'published_at' => $post->published_at,
        ]);
    }
}
