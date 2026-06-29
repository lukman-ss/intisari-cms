<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Plugins\PluginManager;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PluginController
{
    private PluginManager $manager;

    public function __construct()
    {
        $this->manager = new PluginManager();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_PLUGINS)) {
            Flash::set('error', 'You do not have permission to manage plugins.');
            return Redirect::to('/admin/dashboard');
        }

        $plugins = $this->manager->all();
        $activePlugins = array_map(fn($p) => $p->slug, $this->manager->getActivePlugins());

        $content = app()->render('admin/plugins/index', [
            'plugins' => $plugins,
            'activePlugins' => $activePlugins
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Plugins',
            'content' => $content
        ]);
    }

    public function activate(Request $request, string $slug): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_PLUGINS)) {
            return Redirect::to('/admin/dashboard');
        }

        if ($this->manager->activate($slug)) {
            Flash::set('success', "Plugin '{$slug}' activated successfully.");
        } else {
            Flash::set('error', "Plugin '{$slug}' could not be activated. Check if entry file exists.");
        }

        return Redirect::back('/admin/plugins');
    }

    public function deactivate(Request $request, string $slug): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_PLUGINS)) {
            return Redirect::to('/admin/dashboard');
        }

        if ($this->manager->deactivate($slug)) {
            Flash::set('success', "Plugin '{$slug}' deactivated successfully.");
        } else {
            Flash::set('error', "Plugin '{$slug}' could not be deactivated.");
        }

        return Redirect::back('/admin/plugins');
    }
}
