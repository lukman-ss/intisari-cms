<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\Auth;
use Intisari\Application;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class LogoutController
{
    private Application $app;

    public function __construct()
    {
        $this->app = Application::getGlobal() ?? throw new \RuntimeException('No global application instance.');
    }

    public function logout(Request $request): Response
    {
        Auth::logout();

        return new RedirectResponse('/admin/login');
    }
}
