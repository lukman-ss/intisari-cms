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

class PageController
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
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'You do not have permission to manage pages.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $paginator = $this->repo->paginate(PostType::PAGE, $page, 20, (string)$search, (string)$status);

        $content = app()->render('admin/pages/index', [
            'pages' => $paginator['data'],
            'search' => $search,
            'status' => $status,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Pages',
            'content' => $content
        ]);
    }

    public function create(): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'You do not have permission to create pages.');
            return Redirect::to('/admin/dashboard');
        }

        $allPages = $this->repo->paginate(PostType::PAGE, 1, 100, '', PostStatus::PUBLISHED)['data'];

        $content = app()->render('admin/pages/create', ['allPages' => $allPages]);
        return app()->render('layouts/admin', [
            'title' => 'Add New Page',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $data = $_POST;
        $data['type'] = PostType::PAGE;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }
        
        $data['parent_id'] = (int)($data['parent_id'] ?? 0);
        $data['menu_order'] = (int)($data['menu_order'] ?? 0);

        $user = AuthManager::guard()->user();
        $data['author_id'] = $user['id'] ?? 1;

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/pages/create');
        }

        $this->repo->create($data);
        Flash::set('success', 'Page created successfully.');
        return Redirect::to('/admin/pages');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $pageData = $this->repo->find((int)$id);
        if (!$pageData || $pageData->type !== PostType::PAGE) {
            Flash::set('error', 'Page not found.');
            return Redirect::to('/admin/pages');
        }

        $allPages = $this->repo->paginate(PostType::PAGE, 1, 100, '', PostStatus::PUBLISHED)['data'];

        $content = app()->render('admin/pages/edit', [
            'page' => $pageData,
            'allPages' => $allPages,
        ]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Page',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $pageData = $this->repo->find((int)$id);
        if (!$pageData || $pageData->type !== PostType::PAGE) {
            return Redirect::to('/admin/pages');
        }

        $data = $_POST;
        $data['type'] = PostType::PAGE;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }

        $data['parent_id'] = (int)($data['parent_id'] ?? 0);
        $data['menu_order'] = (int)($data['menu_order'] ?? 0);

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED && empty($pageData->published_at)) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data, (int)$id);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back("/admin/pages/{$id}/edit");
        }

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Page updated successfully.');
        return Redirect::to("/admin/pages/{$id}/edit");
    }

    public function trash(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/pages');
        }

        $this->repo->trash((int)$id);
        Flash::set('success', 'Page moved to trash.');
        return Redirect::to('/admin/pages');
    }

    public function restore(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/pages');
        }

        $this->repo->update((int)$id, ['status' => PostStatus::DRAFT]);
        Flash::set('success', 'Page restored from trash.');
        return Redirect::to('/admin/pages');
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/pages');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Page deleted permanently.');
        return Redirect::to('/admin/pages?status=trash');
    }
}
