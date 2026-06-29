<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\InstallerService;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class InstallController
{
    public function index(): string|Response
    {
        $installer = new InstallerService();
        if ($installer->isInstalled()) {
            return Redirect::to('/');
        }

        $requirements = $installer->checkRequirements();
        return app()->render('install/index', ['requirements' => $requirements]);
    }

    public function store(Request $request): Response
    {
        $installer = new InstallerService();
        if ($installer->isInstalled()) {
            return Redirect::to('/');
        }

        $requirements = $installer->checkRequirements();
        if (in_array(false, $requirements, true)) {
            return Redirect::to('/install');
        }

        $data = [
            'site_title' => $_POST['site_title'] ?? '',
            'admin_name' => $_POST['admin_name'] ?? '',
            'admin_email' => $_POST['admin_email'] ?? '',
            'admin_username' => $_POST['admin_username'] ?? '',
            'admin_password' => $_POST['admin_password'] ?? '',
            'db_driver' => $_POST['db_driver'] ?? 'sqlite',
            'db_path' => $_POST['db_path'] ?? 'database/cms.sqlite',
        ];

        try {
            $installer->install($data);
            return Redirect::to('/install/done');
        } catch (\Exception $e) {
            return Redirect::to('/install');
        }
    }

    public function done(): string|Response
    {
        $installer = new InstallerService();
        if (!$installer->isInstalled()) {
            return Redirect::to('/install');
        }

        return app()->render('install/done');
    }
}
