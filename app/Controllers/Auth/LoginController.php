<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\AuthManager;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class LoginController
{
    public function show(): string|Response
    {
        if (AuthManager::guard()->check()) {
            return Redirect::to('/admin/dashboard');
        }

        return app()->render('auth/login');
    }

    public function authenticate(Request $request): Response
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (AuthManager::guard()->attempt($username, $password)) {
            return Redirect::to('/admin/dashboard');
        }

        Flash::set('error', 'Invalid credentials.');
        return Redirect::back('/admin/login');
    }
}
