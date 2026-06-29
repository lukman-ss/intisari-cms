<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\TermRepository;
use App\Support\Flash;
use App\Support\Redirect;
use App\Support\Slug;
use Lukman\Http\Request;
use Lukman\Http\Response;

class CategoryController
{
    private TermRepository $repo;

    public function __construct()
    {
        $this->repo = new TermRepository();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission to manage categories.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';

        $paginator = $this->repo->paginate('category', $page, 20, (string)$search);

        $content = app()->render('admin/categories/index', [
            'categories' => $paginator['data'],
            'search' => $search,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Categories',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $data = $_POST;
        if (empty($data['name'])) {
            Flash::set('error', 'Name is required.');
            return Redirect::back('/admin/categories');
        }

        $data['taxonomy'] = 'category';
        $data['slug'] = empty($data['slug']) ? Slug::generate($data['name']) : Slug::generate($data['slug']);

        $this->repo->create($data);
        Flash::set('success', 'Category created successfully.');
        return Redirect::to('/admin/categories');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $category = $this->repo->find((int)$id);
        if (!$category || $category['taxonomy'] !== 'category') {
            Flash::set('error', 'Category not found.');
            return Redirect::to('/admin/categories');
        }

        $content = app()->render('admin/categories/edit', ['category' => $category]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Category',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $category = $this->repo->find((int)$id);
        if (!$category || $category['taxonomy'] !== 'category') {
            return Redirect::to('/admin/categories');
        }

        $data = $_POST;
        if (empty($data['name'])) {
            Flash::set('error', 'Name is required.');
            return Redirect::back("/admin/categories/{$id}/edit");
        }

        $data['slug'] = empty($data['slug']) ? Slug::generate($data['name']) : Slug::generate($data['slug']);
        
        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Category updated successfully.');
        return Redirect::to("/admin/categories/{$id}/edit");
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        if ((int)$id === 1) {
            Flash::set('error', 'Cannot delete the default category.');
            return Redirect::to('/admin/categories');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Category deleted.');
        return Redirect::to('/admin/categories');
    }
}
