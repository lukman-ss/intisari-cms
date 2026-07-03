<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\PostRepository;
use App\Repositories\RevisionRepository;
use App\Repositories\TermRepository;
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
    private TermRepository $termRepo;

    public function __construct()
    {
        $this->repo = new PostRepository();
        $this->validator = new PostValidator();
        $this->termRepo = new TermRepository();
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

        $allCategories = $this->termRepo->allByTaxonomy('category');
        $allTags = $this->termRepo->allByTaxonomy('post_tag');

        $content = app()->render('admin/posts/create', [
            'allCategories' => $allCategories,
            'allTags'       => $allTags,
        ]);
        return app()->render('layouts/admin', [
            'title'   => 'Add New Post',
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

        $postId = $this->repo->create($data);

        // Sync categories and tags
        $termIds = array_merge(
            is_array($_POST['categories'] ?? null) ? $_POST['categories'] : [],
            is_array($_POST['tags'] ?? null) ? $_POST['tags'] : []
        );
        $this->termRepo->syncTerms($postId, $termIds);

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

        $allCategories    = $this->termRepo->allByTaxonomy('category');
        $allTags          = $this->termRepo->allByTaxonomy('post_tag');
        $postCategories   = array_column($this->termRepo->getTermsForPost((int)$id, 'category'), 'id');
        $postTags         = array_column($this->termRepo->getTermsForPost((int)$id, 'post_tag'), 'id');

        $content = app()->render('admin/posts/edit', [
            'post'           => $post,
            'allCategories'  => $allCategories,
            'allTags'        => $allTags,
            'postCategories' => $postCategories,
            'postTags'       => $postTags,
        ]);
        return app()->render('layouts/admin', [
            'title'   => 'Edit Post',
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

        // Sync terms
        $termIds = array_merge(
            is_array($_POST['categories'] ?? null) ? $_POST['categories'] : [],
            is_array($_POST['tags'] ?? null) ? $_POST['tags'] : []
        );
        $this->termRepo->syncTerms((int)$id, $termIds);

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

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::DELETE_POSTS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/posts');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No posts selected.');
            return \App\Support\Redirect::to('/admin/posts');
        }

        foreach ($ids as $id) {
            $post = $this->repo->find((int)$id);
            if ($post && $post->type === \App\Support\PostType::POST) {
                if ($action === 'trash') {
                    $this->repo->trash((int)$id);
                } elseif ($action === 'restore') {
                    $this->repo->update((int)$id, ['status' => \App\Support\PostStatus::DRAFT]);
                } elseif ($action === 'delete') {
                    $this->repo->delete((int)$id);
                }
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/posts');
    }

    public function autosave(Request $request, string $id): Response
    {
        header('Content-Type: application/json');

        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            return new Response(json_encode(['success' => false, 'message' => 'Permission denied.']), 403);
        }

        $post = $this->repo->find((int)$id);
        if (!$post || $post->type !== PostType::POST) {
            return new Response(json_encode(['success' => false, 'message' => 'Post not found.']), 404);
        }

        $data = [
            'title'   => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
        ];

        $revRepo = new RevisionRepository();
        $autosaveId = $revRepo->createAutosave((int)$id, $data);

        return new Response(json_encode([
            'success'     => true,
            'autosave_id' => $autosaveId,
            'message'     => 'Draft saved.',
        ]));
    }
}