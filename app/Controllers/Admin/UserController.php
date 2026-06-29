<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\PasswordHasher;
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

    public function __construct()
    {
        $this->repo = new UserRepository();
        $this->validator = new UserValidator();
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
        $content = app()->render('admin/users/create');
        return app()->render('layouts/admin', [
            'title' => 'Add New User',
            'content' => $content
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

        $content = app()->render('admin/users/edit', ['user' => $user]);
        return app()->render('layouts/admin', [
            'title' => 'Edit User',
            'content' => $content
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
}
