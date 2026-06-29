<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\Post;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class PostController extends BaseController
{
    private function model(): Post
    {
        return new Post($this->app->db());
    }

    private function flashError(): ?string
    {
        $session = $this->app->session();
        return $session->started() ? $session->get('_flash_error') : null;
    }

    public function index(Request $request): Response
    {
        $html = $this->app->render('admin.posts.index', [
            'authUser' => Auth::user(),
            'posts'    => $this->model()->all(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function create(Request $request): Response
    {
        $html = $this->app->render('admin.posts.create', [
            'authUser' => Auth::user(),
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function store(Request $request): Response
    {
        $data    = $request->only(['title', 'slug', 'excerpt', 'content', 'status']);
        $session = $this->app->session();

        try {
            $this->app->validate($data, [
                'title' => 'required',
                'slug'  => 'required',
            ]);
        } catch (\Lukman\Validation\Exception\ValidationException $e) {
            if ($session->started()) {
                $session->flash('_flash_error', implode(', ', $e->errors()->all()));
            }
            return new RedirectResponse('/admin/posts/create');
        }

        $data['author_id'] = Auth::id() ?? 0;
        $data['status']    = $data['status'] !== '' ? $data['status'] : 'draft';

        $this->model()->create($data);

        return new RedirectResponse('/admin/posts');
    }

    public function edit(Request $request, string $id): Response
    {
        $post = $this->model()->find((int) $id);

        if ($post === null) {
            return new Response('Post not found.', 404);
        }

        $html = $this->app->render('admin.posts.edit', [
            'authUser' => Auth::user(),
            'post'     => $post,
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function update(Request $request, string $id): Response
    {
        $data = array_filter(
            $request->only(['title', 'slug', 'excerpt', 'content', 'status']),
            fn ($v) => $v !== null && $v !== ''
        );

        $this->model()->update((int) $id, $data);

        return new RedirectResponse('/admin/posts');
    }

    public function destroy(Request $request, string $id): Response
    {
        $this->model()->delete((int) $id);

        return new RedirectResponse('/admin/posts');
    }
}
