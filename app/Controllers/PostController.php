<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PostController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    public function index(Request $request): string|Response
    {
        $page = (int)($_GET['page'] ?? 1);
        $paginator = $this->repo->paginate(PostType::POST, $page, 10, '', PostStatus::PUBLISHED);

        $content = app()->render('site/posts/index', [
            'posts' => $paginator['data'],
            'paginator' => $paginator,
        ]);
        
        return app()->render('layouts/site', [
            'title' => 'Blog',
            'content' => $content
        ]);
    }

    public function show(Request $request, string $slug): string|Response
    {
        $post = $this->repo->findBySlug($slug, PostType::POST);
        
        if (!$post || $post->status !== PostStatus::PUBLISHED) {
            return '404 Not Found';
        }

        $content = app()->render('site/posts/show', ['post' => $post]);
        return app()->render('layouts/site', [
            'title' => $post->title,
            'content' => $content
        ]);
    }
}
