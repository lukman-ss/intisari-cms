<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PageController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    public function show(Request $request, string $slug): string|Response
    {
        $page = $this->repo->findBySlug($slug, PostType::PAGE);
        
        if (!$page || $page->status !== PostStatus::PUBLISHED) {
            return '404 Not Found';
        }

        $content = app()->render('site/pages/show', ['page' => $page]);
        return app()->render('layouts/site', [
            'title' => $page->title,
            'content' => $content
        ]);
    }
}
