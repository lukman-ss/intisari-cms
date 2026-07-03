<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\PasswordHasher;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Support\Flash;
use App\Support\Redirect;
use App\Validation\UserValidator;
use Lukman\Http\Request;
use Lukman\Http\Response;

class UserController
{
    private UserRepository $repo;
    private UserValidator $validator;
    private RoleRepository $roleRepo;

    public function __construct()
    {
        $this->repo = new UserRepository();
        $this->validator = new UserValidator();
        $this->roleRepo = new RoleRepository();
    }

    public function index(Request $request): string|Response
    {
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        
        $paginator = $this->repo->paginate($page, 20, (string)$search);

        $content = app()->render('admin/users/index', [
            'users' => $paginator['data'],
            'search' => $search,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Users',
            'content' => $content
        ]);
    }

    public function create(): string|Response
    {
        $roles = $this->roleRepo->all();
        $content = app()->render('admin/users/create', ['roles' => $roles]);
        return app()->render('layouts/admin', [
            'title'   => 'Add New User',
            'content' => $content,
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $_POST;
        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/users/create');
        }

        $hasher = new PasswordHasher();
        $data['password'] = $hasher->make($data['password']);

        $this->repo->create($data);
        Flash::set('success', 'User created successfully.');
        return Redirect::to('/admin/users');
    }

    public function edit(Request $request, string $id): string|Response
    {
        $user = $this->repo->find((int)$id);
        if (!$user) {
            Flash::set('error', 'User not found.');
            return Redirect::to('/admin/users');
        }

        $roles = $this->roleRepo->all();
        $content = app()->render('admin/users/edit', ['user' => $user, 'roles' => $roles]);
        return app()->render('layouts/admin', [
            'title'   => 'Edit User',
            'content' => $content,
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $user = $this->repo->find((int)$id);
        if (!$user) {
            return Redirect::to('/admin/users');
        }

        $data = $_POST;
        $errors = $this->validator->validate($data, (int)$id);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back("/admin/users/{$id}/edit");
        }

        if (!empty($data['password'])) {
            $hasher = new PasswordHasher();
            $data['password'] = $hasher->make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'User updated successfully.');
        return Redirect::to("/admin/users/{$id}/edit");
    }

    public function destroy(Request $request, string $id): Response
    {
        $currentUser = AuthManager::guard()->user();
        if ($currentUser && (int)$currentUser['id'] === (int)$id) {
            Flash::set('error', 'You cannot delete yourself.');
            return Redirect::to('/admin/users');
        }

        if ($this->repo->countAdmins() <= 1) {
            Flash::set('error', 'Cannot delete the last administrator.');
            return Redirect::to('/admin/users');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'User deleted.');
        return Redirect::to('/admin/users');
    }

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::MANAGE_USERS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/users');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No users selected.');
            return \App\Support\Redirect::to('/admin/users');
        }

        if ($action === 'delete') {
            foreach ($ids as $id) {
                if ((int)$id === 1) {
                    \App\Support\Flash::set('error', 'Cannot delete the main admin user.');
                    continue;
                }
                if ((int)$id === \App\Auth\AuthManager::guard()->id()) {
                    \App\Support\Flash::set('error', 'Cannot delete yourself.');
                    continue;
                }
                $this->repo->delete((int)$id);
            }
            \App\Support\Flash::set('success', 'Bulk action completed.');
        }
        return \App\Support\Redirect::back('/admin/users');
    }
}