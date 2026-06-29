<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Auth\AuthManager;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class LogoutController
{
    public function logout(Request $request): Response
    {
        AuthManager::guard()->logout();
        return Redirect::to('/admin/login');
    }
}
