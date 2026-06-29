<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Themes\ThemeManager;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ThemeController
{
    private ThemeManager $manager;

    public function __construct()
    {
        $this->manager = new ThemeManager();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_THEMES)) {
            Flash::set('error', 'You do not have permission to manage themes.');
            return Redirect::to('/admin/dashboard');
        }

        $themes = $this->manager->all();
        try {
            $activeTheme = $this->manager->getActiveTheme();
        } catch (\Exception $e) {
            $activeTheme = null;
        }

        $content = app()->render('admin/themes/index', [
            'themes' => $themes,
            'activeTheme' => $activeTheme
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Themes',
            'content' => $content
        ]);
    }

    public function activate(Request $request, string $slug): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_THEMES)) {
            return Redirect::to('/admin/dashboard');
        }

        if ($this->manager->activate($slug)) {
            Flash::set('success', "Theme '{$slug}' activated successfully.");
        } else {
            Flash::set('error', "Theme '{$slug}' could not be activated.");
        }

        return Redirect::to('/admin/appearance/themes');
    }
}
