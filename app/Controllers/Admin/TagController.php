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

class TagController
{
    private TermRepository $repo;

    public function __construct()
    {
        $this->repo = new TermRepository();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission to manage tags.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';

        $paginator = $this->repo->paginate('tag', $page, 20, (string)$search);

        $content = app()->render('admin/tags/index', [
            'tags' => $paginator['data'],
            'search' => $search,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Tags',
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
            return Redirect::back('/admin/tags');
        }

        $data['taxonomy'] = 'tag';
        $data['slug'] = empty($data['slug']) ? Slug::generate($data['name']) : Slug::generate($data['slug']);

        $seoData = [
            'seo_title'       => $_POST['seo_title'] ?? '',
            'seo_description' => $_POST['seo_description'] ?? '',
            'seo_keywords'    => $_POST['seo_keywords'] ?? '',
            'seo_noindex'     => isset($_POST['seo_noindex']) ? 1 : 0,
            'seo_nofollow'    => isset($_POST['seo_nofollow']) ? 1 : 0,
            'seo_canonical'   => trim($_POST['seo_canonical'] ?? ''),
            'seo_og_title'    => $_POST['seo_og_title'] ?? '',
            'seo_og_description' => $_POST['seo_og_description'] ?? '',
            'seo_og_image'    => $_POST['seo_og_image'] ?? ''
        ];
        $data['seo_metadata'] = json_encode($seoData);
        foreach (array_keys($seoData) as $k) { unset($data[$k]); }

        $this->repo->create($data);
        Flash::set('success', 'Tag created successfully.');
        return Redirect::to('/admin/tags');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $tag = $this->repo->find((int)$id);
        if (!$tag || $tag['taxonomy'] !== 'tag') {
            Flash::set('error', 'Tag not found.');
            return Redirect::to('/admin/tags');
        }

        $content = app()->render('admin/tags/edit', ['tag' => $tag]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Tag',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $tag = $this->repo->find((int)$id);
        if (!$tag || $tag['taxonomy'] !== 'tag') {
            return Redirect::to('/admin/tags');
        }

        $data = $_POST;
        if (empty($data['name'])) {
            Flash::set('error', 'Name is required.');
            return Redirect::back("/admin/tags/{$id}/edit");
        }

        $data['slug'] = empty($data['slug']) ? Slug::generate($data['name']) : Slug::generate($data['slug']);
        
        $seoData = [
            'seo_title'       => $_POST['seo_title'] ?? '',
            'seo_description' => $_POST['seo_description'] ?? '',
            'seo_keywords'    => $_POST['seo_keywords'] ?? '',
            'seo_noindex'     => isset($_POST['seo_noindex']) ? 1 : 0,
            'seo_nofollow'    => isset($_POST['seo_nofollow']) ? 1 : 0,
            'seo_canonical'   => trim($_POST['seo_canonical'] ?? ''),
            'seo_og_title'    => $_POST['seo_og_title'] ?? '',
            'seo_og_description' => $_POST['seo_og_description'] ?? '',
            'seo_og_image'    => $_POST['seo_og_image'] ?? ''
        ];
        $data['seo_metadata'] = json_encode($seoData);
        foreach (array_keys($seoData) as $k) { unset($data[$k]); }

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Tag updated successfully.');
        return Redirect::to("/admin/tags/{$id}/edit");
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Tag deleted.');
        return Redirect::to('/admin/tags');
    }
}
