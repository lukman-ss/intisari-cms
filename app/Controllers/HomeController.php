<?php

declare(strict_types=1);

namespace App\Controllers;

use Lukman\Http\Request;
use Lukman\Http\Response;

class HomeController
{
    public function index(Request $request): string|Response
    {
        $content = app()->render('site/home');
        return app()->render('layouts/site', [
            'title' => 'Home',
            'content' => $content
        ]);
    }
}
