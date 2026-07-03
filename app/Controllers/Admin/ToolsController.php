<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Database\ConnectionFactory;
use App\Repositories\PostRepository;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ToolsController
{
    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/dashboard');
        }

        $content = app()->render('admin/tools/index');
        return app()->render('layouts/admin', [
            'title'   => 'Tools',
            'content' => $content,
        ]);
    }

    public function export(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $db = ConnectionFactory::make();
        $type = $_GET['type'] ?? 'all';

        $data = ['exported_at' => date('c'), 'type' => $type];

        if ($type === 'posts' || $type === 'all') {
            $stmt = $db->query("SELECT * FROM posts WHERE type = 'post' AND status != 'trash' ORDER BY id ASC");
            $data['posts'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if ($type === 'pages' || $type === 'all') {
            $stmt = $db->query("SELECT * FROM posts WHERE type = 'page' AND status != 'trash' ORDER BY id ASC");
            $data['pages'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if ($type === 'users' || $type === 'all') {
            $stmt = $db->query("SELECT id, username, email, role, status, created_at FROM users ORDER BY id ASC");
            $data['users'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if ($type === 'terms' || $type === 'all') {
            $stmt = $db->query("SELECT * FROM terms ORDER BY id ASC");
            $data['terms'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if ($type === 'settings' || $type === 'all') {
            $stmt = $db->query("SELECT option_name, option_value FROM options ORDER BY option_name ASC");
            $data['settings'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $filename = 'intisari-export-' . date('Y-m-d') . '-' . $type . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($json));

        return new Response($json);
    }

    public function import(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        if (empty($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            Flash::set('error', 'No file uploaded or upload error.');
            return Redirect::to('/admin/tools');
        }

        $file = $_FILES['import_file'];
        if ($file['type'] !== 'application/json' && !str_ends_with($file['name'], '.json')) {
            Flash::set('error', 'Only JSON files are accepted.');
            return Redirect::to('/admin/tools');
        }

        $rawJson = file_get_contents($file['tmp_name']);
        $importData = json_decode($rawJson, true);

        if (!is_array($importData)) {
            Flash::set('error', 'Invalid JSON file.');
            return Redirect::to('/admin/tools');
        }

        $db = ConnectionFactory::make();
        $imported = 0;

        // Import posts
        if (!empty($importData['posts']) && is_array($importData['posts'])) {
            $repo = new PostRepository();
            foreach ($importData['posts'] as $post) {
                unset($post['id']);
                $post['type'] = 'post';
                $repo->create($post);
                $imported++;
            }
        }

        // Import pages
        if (!empty($importData['pages']) && is_array($importData['pages'])) {
            $repo = new PostRepository();
            foreach ($importData['pages'] as $page) {
                unset($page['id']);
                $page['type'] = 'page';
                $repo->create($page);
                $imported++;
            }
        }

        Flash::set('success', "Import complete. {$imported} items imported.");
        return Redirect::to('/admin/tools');
    }
}
