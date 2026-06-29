<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\User;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class UserController extends BaseController
{
    private function model(): User
    {
        return new User($this->app->db());
    }

    private function flashError(): ?string
    {
        $session = $this->app->session();
        return $session->started() ? $session->get('_flash_error') : null;
    }

    public function index(Request $request): Response
    {
        $html = $this->app->render('admin.users.index', [
            'authUser' => Auth::user(),
            'users'    => $this->model()->all(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function create(Request $request): Response
    {
        $html = $this->app->render('admin.users.create', [
            'authUser' => Auth::user(),
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function store(Request $request): Response
    {
        $data    = $request->only(['name', 'email', 'password']);
        $session = $this->app->session();

        try {
            $this->app->validate($data, [
                'name'     => 'required',
                'email'    => 'required',
                'password' => 'required',
            ]);
        } catch (\Lukman\Validation\Exception\ValidationException $e) {
            if ($session->started()) {
                $session->flash('_flash_error', implode(', ', $e->errors()->all()));
            }
            return new RedirectResponse('/admin/users/create');
        }

        $this->model()->create($data);

        return new RedirectResponse('/admin/users');
    }

    public function edit(Request $request, string $id): Response
    {
        $user = $this->model()->find((int) $id);

        if ($user === null) {
            return new Response('User not found.', 404);
        }

        $html = $this->app->render('admin.users.edit', [
            'authUser' => Auth::user(),
            'editUser' => $user,
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function update(Request $request, string $id): Response
    {
        $data = array_filter(
            $request->only(['name', 'email', 'password']),
            fn ($v) => $v !== null && $v !== ''
        );

        $this->model()->update((int) $id, $data);

        return new RedirectResponse('/admin/users');
    }

    public function destroy(Request $request, string $id): Response
    {
        $this->model()->delete((int) $id);

        return new RedirectResponse('/admin/users');
    }
}
