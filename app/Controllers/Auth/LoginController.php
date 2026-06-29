<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Models\User;
use Intisari\Application;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class LoginController
{
    private Application $app;

    public function __construct()
    {
        $this->app = Application::getGlobal() ?? throw new \RuntimeException('No global application instance.');
    }

    public function showForm(Request $request): Response
    {
        $session = $this->app->session();
        $error   = null;

        if ($session->started()) {
            $error = $session->get('_flash_error');
        }

        $html = $this->app->render('auth.login', [
            'error'   => $error,
            'appName' => $this->app->config()->get('app.name', 'Intisari CMS'),
        ]);

        return new Response($html);
    }

    public function login(Request $request): Response
    {
        $email    = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');
        $session  = $this->app->session();

        if ($email === '' || $password === '') {
            if ($session->started()) {
                $session->flash('_flash_error', 'Email and password are required.');
            }
            return new RedirectResponse('/admin/login');
        }

        $userModel = new User($this->app->db());
        $user      = $userModel->findByEmail($email);

        if ($user === null || !$userModel->verifyPassword($user, $password)) {
            if ($session->started()) {
                $session->flash('_flash_error', 'Invalid credentials.');
            }
            return new RedirectResponse('/admin/login');
        }

        Auth::login($user);

        if ($session->started()) {
            $session->regenerate();
        }

        return new RedirectResponse('/admin/dashboard');
    }
}
