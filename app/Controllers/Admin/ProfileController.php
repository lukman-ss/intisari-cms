<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\PasswordHasher;
use App\Repositories\UserRepository;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ProfileController
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function show(Request $request): string|Response
    {
        $user = AuthManager::guard()->user();
        if (!$user) {
            return Redirect::to('/admin/login');
        }

        $fullUser = $this->repo->find((int)$user['id']);
        if (!$fullUser) {
            Flash::set('error', 'User not found.');
            return Redirect::to('/admin/dashboard');
        }

        $content = app()->render('admin/profile/show', ['user' => $fullUser]);
        return app()->render('layouts/admin', [
            'title'   => 'My Profile',
            'content' => $content,
        ]);
    }

    public function update(Request $request): Response
    {
        $authUser = AuthManager::guard()->user();
        if (!$authUser) {
            return Redirect::to('/admin/login');
        }

        $id = (int)$authUser['id'];
        $data = $_POST;

        // Validate
        $errors = [];
        if (empty($data['username'])) {
            $errors[] = 'Username is required.';
        }
        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        }
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if (!empty($data['password']) && $data['password'] !== ($data['password_confirm'] ?? '')) {
            $errors[] = 'Passwords do not match.';
        }

        // Check username uniqueness
        $existing = $this->repo->findByUsername($data['username'], $id);
        if ($existing) {
            $errors[] = 'Username already taken.';
        }
        $existingEmail = $this->repo->findByEmail($data['email'], $id);
        if ($existingEmail) {
            $errors[] = 'Email already in use.';
        }

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/profile');
        }

        $updateData = [
            'username' => $data['username'],
            'email'    => $data['email'],
        ];

        if (!empty($data['password'])) {
            $hasher = new PasswordHasher();
            $updateData['password'] = $hasher->make($data['password']);
        }

        $this->repo->update($id, $updateData);
        Flash::set('success', 'Profile updated successfully.');
        return Redirect::to('/admin/profile');
    }
}
