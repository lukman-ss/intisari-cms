<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\RedirectRepository;
use App\Support\Flash;
use App\Support\Redirect as HttpRedirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class RedirectController
{
    private RedirectRepository $repo;

    public function __construct()
    {
        $this->repo = new RedirectRepository();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission to manage redirects.');
            return HttpRedirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';

        $paginator = $this->repo->paginate($page, 20, (string)$search);

        $content = app()->render('admin/redirects/index', [
            'redirects' => $paginator['data'],
            'search' => $search,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'SEO Redirects',
            'content' => $content
        ]);
    }

    public function create(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return HttpRedirect::to('/admin/dashboard');
        }

        $content = app()->render('admin/redirects/create');
        return app()->render('layouts/admin', [
            'title' => 'Add New Redirect',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return HttpRedirect::to('/admin/dashboard');
        }

        $data = $_POST;
        if (empty($data['source_url']) || empty($data['target_url'])) {
            Flash::set('error', 'Source and Target URLs are required.');
            return HttpRedirect::back('/admin/redirects/create');
        }

        $data['source_url'] = '/' . ltrim(parse_url($data['source_url'], PHP_URL_PATH) ?? '', '/');

        $this->repo->create($data);
        Flash::set('success', 'Redirect created successfully.');
        return HttpRedirect::to('/admin/redirects');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return HttpRedirect::to('/admin/dashboard');
        }

        $redirect = $this->repo->find((int)$id);
        if (!$redirect) {
            Flash::set('error', 'Redirect not found.');
            return HttpRedirect::to('/admin/redirects');
        }

        $content = app()->render('admin/redirects/edit', ['redirect' => $redirect]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Redirect',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return HttpRedirect::to('/admin/dashboard');
        }

        $redirect = $this->repo->find((int)$id);
        if (!$redirect) {
            return HttpRedirect::to('/admin/redirects');
        }

        $data = $_POST;
        if (empty($data['source_url']) || empty($data['target_url'])) {
            Flash::set('error', 'Source and Target URLs are required.');
            return HttpRedirect::back("/admin/redirects/{$id}/edit");
        }

        $data['source_url'] = '/' . ltrim(parse_url($data['source_url'], PHP_URL_PATH) ?? '', '/');

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Redirect updated successfully.');
        return HttpRedirect::to('/admin/redirects');
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return HttpRedirect::to('/admin/dashboard');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Redirect deleted.');
        return HttpRedirect::to('/admin/redirects');
    }
}
