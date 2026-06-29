<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class DashboardController extends BaseController
{
    public function index(Request $request): Response
    {
        $db = $this->app->db();

        $userCount = count((new User($db))->all());
        $pageCount = count((new Page($db))->all());
        $postCount = count((new Post($db))->all());

        $html = $this->app->render('admin.dashboard', [
            'authUser'  => Auth::user(),
            'userCount' => $userCount,
            'pageCount' => $pageCount,
            'postCount' => $postCount,
            'appName'   => $this->appName(),
        ]);

        return new Response($html);
    }
}
