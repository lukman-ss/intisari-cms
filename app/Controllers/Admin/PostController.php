<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\PostRepository;
use App\Support\Flash;
use App\Support\PostStatus;
use App\Support\PostType;
use App\Support\Redirect;
use App\Support\Slug;
use App\Validation\PostValidator;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PostController
{
    private PostRepository $repo;
    private PostValidator $validator;

    public function __construct()
    {
        $this->repo = new PostRepository();
        $this->validator = new PostValidator();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            Flash::set('error', 'You do not have permission to manage posts.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $paginator = $this->repo->paginate(PostType::POST, $page, 20, (string)$search, (string)$status);

        $content = app()->render('admin/posts/index', [
            'posts' => $paginator['data'],
            'search' => $search,
            'status' => $status,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Posts',
            'content' => $content
        ]);
    }

    public function create(): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            Flash::set('error', 'You do not have permission to create posts.');
            return Redirect::to('/admin/dashboard');
        }

        $content = app()->render('admin/posts/create');
        return app()->render('layouts/admin', [
            'title' => 'Add New Post',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            return Redirect::to('/admin/dashboard');
        }

        $data = $_POST;
        $data['type'] = PostType::POST;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }

        $user = AuthManager::guard()->user();
        $data['author_id'] = $user['id'] ?? 1;

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/posts/create');
        }

        $this->repo->create($data);
        Flash::set('success', 'Post created successfully.');
        return Redirect::to('/admin/posts');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            return Redirect::to('/admin/dashboard');
        }

        $post = $this->repo->find((int)$id);
        if (!$post || $post->type !== PostType::POST) {
            Flash::set('error', 'Post not found.');
            return Redirect::to('/admin/posts');
        }

        $content = app()->render('admin/posts/edit', ['post' => $post]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Post',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            return Redirect::to('/admin/dashboard');
        }

        $post = $this->repo->find((int)$id);
        if (!$post || $post->type !== PostType::POST) {
            return Redirect::to('/admin/posts');
        }

        $data = $_POST;
        $data['type'] = PostType::POST;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED && empty($post->published_at)) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data, (int)$id);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back("/admin/posts/{$id}/edit");
        }

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Post updated successfully.');
        return Redirect::to("/admin/posts/{$id}/edit");
    }

    public function trash(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::DELETE_POSTS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/posts');
        }

        $this->repo->trash((int)$id);
        Flash::set('success', 'Post moved to trash.');
        return Redirect::to('/admin/posts');
    }

    public function restore(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::DELETE_POSTS)) {
            return Redirect::to('/admin/posts');
        }

        $this->repo->update((int)$id, ['status' => PostStatus::DRAFT]);
        Flash::set('success', 'Post restored from trash.');
        return Redirect::to('/admin/posts');
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::DELETE_POSTS)) {
            return Redirect::to('/admin/posts');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Post deleted permanently.');
        return Redirect::to('/admin/posts?status=trash');
    }
}
