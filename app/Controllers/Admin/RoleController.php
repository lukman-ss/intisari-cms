<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\RoleRepository;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class RoleController
{
    private RoleRepository $repo;

    public function __construct()
    {
        $this->repo = new RoleRepository();
    }

    public function index(): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_USERS)) {
            Flash::set('error', 'You do not have permission to manage roles.');
            return Redirect::to('/admin/dashboard');
        }

        $roles = $this->repo->all();
        $content = app()->render('admin/roles/index', ['roles' => $roles]);

        return app()->render('layouts/admin', [
            'title' => 'Roles',
            'content' => $content
        ]);
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_USERS)) {
            Flash::set('error', 'You do not have permission to manage roles.');
            return Redirect::to('/admin/dashboard');
        }

        $role = $this->repo->find((int)$id);
        if (!$role) {
            Flash::set('error', 'Role not found.');
            return Redirect::to('/admin/roles');
        }

        if ($role['name'] === 'administrator') {
            Flash::set('error', 'Administrator capabilities cannot be modified.');
            return Redirect::to('/admin/roles');
        }

        $capabilities = $this->repo->getCapabilities((int)$id);
        $allCapabilities = Capability::all();

        $content = app()->render('admin/roles/edit', [
            'role' => $role,
            'current_capabilities' => $capabilities,
            'all_capabilities' => $allCapabilities,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Edit Role',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_USERS)) {
            Flash::set('error', 'You do not have permission to manage roles.');
            return Redirect::to('/admin/dashboard');
        }

        $role = $this->repo->find((int)$id);
        if (!$role || $role['name'] === 'administrator') {
            return Redirect::to('/admin/roles');
        }

        $capabilities = $_POST['capabilities'] ?? [];
        if (!is_array($capabilities)) {
            $capabilities = [];
        }

        $this->repo->syncCapabilities((int)$id, $capabilities);

        Flash::set('success', 'Role capabilities updated successfully.');
        return Redirect::to("/admin/roles/{$id}/edit");
    }
}
