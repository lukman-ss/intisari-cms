<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\Setting;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class SettingController extends BaseController
{
    private function model(): Setting
    {
        return new Setting($this->app->db());
    }

    public function index(Request $request): Response
    {
        $session = $this->app->session();

        $html = $this->app->render('admin.settings.index', [
            'authUser' => Auth::user(),
            'settings' => $this->model()->all(),
            'success'  => $session->started() ? $session->get('_flash_success') : null,
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function update(Request $request): Response
    {
        $model   = $this->model();
        $updates = $request->input('settings');
        $session = $this->app->session();

        if (is_array($updates)) {
            foreach ($updates as $key => $value) {
                $model->set((string) $key, (string) $value);
            }
        }

        if ($session->started()) {
            $session->flash('_flash_success', 'Settings saved.');
        }

        return new RedirectResponse('/admin/settings');
    }
}
