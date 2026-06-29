<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Http\JsonResponse;
use App\Middleware\ApiAuthMiddleware;
use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PageApiController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    private function protect(Request $request, \Closure $action): JsonResponse
    {
        $middleware = new ApiAuthMiddleware();
        $response = $middleware->handle($request, function($req) use ($action) {
            return $action($req);
        });
        
        if ($response instanceof JsonResponse) {
            return $response;
        }
        
        return new JsonResponse(['message' => (string)$response]);
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

    public function store(Request $request): JsonResponse
    {
        return $this->protect($request, function() use ($request) {
            if (!CapabilityChecker::checkCurrentUser(Capability::CREATE_PAGES)) {
                return new JsonResponse(['error' => 'Forbidden'], 403);
            }

            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            
            $title = trim($input['title'] ?? '');
            if ($title === '') {
                return new JsonResponse(['error' => 'Title is required'], 422);
            }

            $data = [
                'title' => $title,
                'content' => $input['content'] ?? '',
                'status' => in_array($input['status'] ?? '', PostStatus::all()) ? $input['status'] : PostStatus::DRAFT,
                'type' => PostType::PAGE,
            ];

            $id = $this->repo->create($data);
            return new JsonResponse(['id' => $id, 'message' => 'Page created'], 201);
        });
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return $this->protect($request, function() use ($request, $id) {
            if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
                return new JsonResponse(['error' => 'Forbidden'], 403);
            }

            $post = $this->repo->findById((int)$id);
            if (!$post || $post->type !== PostType::PAGE) {
                return new JsonResponse(['error' => 'Not found'], 404);
            }

            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            
            $data = [];
            if (isset($input['title'])) {
                $title = trim($input['title']);
                if ($title === '') {
                    return new JsonResponse(['error' => 'Title cannot be empty'], 422);
                }
                $data['title'] = $title;
            }
            if (isset($input['content'])) {
                $data['content'] = $input['content'];
            }
            if (isset($input['status']) && in_array($input['status'], PostStatus::all())) {
                $data['status'] = $input['status'];
            }

            if (!empty($data)) {
                $this->repo->update((int)$id, $data);
            }

            return new JsonResponse(['message' => 'Page updated']);
        });
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        return $this->protect($request, function() use ($id) {
            if (!CapabilityChecker::checkCurrentUser(Capability::DELETE_PAGES)) {
                return new JsonResponse(['error' => 'Forbidden'], 403);
            }

            $post = $this->repo->findById((int)$id);
            if (!$post || $post->type !== PostType::PAGE) {
                return new JsonResponse(['error' => 'Not found'], 404);
            }

            $this->repo->delete((int)$id);
            return new JsonResponse(['message' => 'Page deleted']);
        });
    }
}
