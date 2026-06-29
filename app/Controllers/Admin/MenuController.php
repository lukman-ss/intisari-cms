<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\MenuRepository;
use App\Database\ConnectionFactory;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class MenuController
{
    private MenuRepository $repo;

    public function __construct()
    {
        $this->repo = new MenuRepository();
    }

    private function getPostsAndPages(): array
    {
        $db = ConnectionFactory::make();
        $stmt = $db->query("SELECT id, title, type, slug FROM posts WHERE type IN ('post', 'page') AND status = 'published' ORDER BY title ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission to manage menus.');
            return Redirect::to('/admin/dashboard');
        }

        $menus = $this->repo->all();

        $content = app()->render('admin/menus/index', ['menus' => $menus]);
        return app()->render('layouts/admin', ['title' => 'Menus', 'content' => $content]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            Flash::set('error', 'Menu name is required.');
            return Redirect::back('/admin/appearance/menus');
        }

        $id = $this->repo->create(['name' => strip_tags($name)]);
        Flash::set('success', 'Menu created.');
        return Redirect::to("/admin/appearance/menus/{$id}");
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $menu = $this->repo->find((int)$id);
        if (!$menu) {
            return Redirect::to('/admin/appearance/menus');
        }

        $menus = $this->repo->all();
        $items = $this->repo->getItems((int)$id);
        $contentList = $this->getPostsAndPages();

        $content = app()->render('admin/menus/edit', [
            'menu' => $menu,
            'menus' => $menus,
            'items' => $items,
            'contentList' => $contentList
        ]);
        return app()->render('layouts/admin', ['title' => 'Edit Menu', 'content' => $content]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $menuId = (int)$id;
        $name = trim($_POST['name'] ?? '');

        if ($name !== '') {
            $this->repo->update($menuId, ['name' => strip_tags($name)]);
        }

        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $itemId => $data) {
                $this->repo->updateItem((int)$itemId, [
                    'title' => strip_tags($data['title'] ?? ''),
                    'url' => strip_tags($data['url'] ?? ''),
                    'order_index' => (int)($data['order_index'] ?? 0),
                    'parent_id' => (int)($data['parent_id'] ?? 0),
                ]);
            }
        }

        Flash::set('success', 'Menu updated.');
        return Redirect::back("/admin/appearance/menus/{$id}");
    }

    public function storeItem(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $menuId = (int)$id;

        // Custom Link
        if (!empty($_POST['custom_url']) && !empty($_POST['custom_title'])) {
            $this->repo->addItem($menuId, [
                'title' => strip_tags($_POST['custom_title']),
                'url' => strip_tags($_POST['custom_url']),
                'parent_id' => 0,
                'order_index' => 0
            ]);
        }
        
        // Page/Post Link
        if (!empty($_POST['content_ids']) && is_array($_POST['content_ids'])) {
            $contentList = $this->getPostsAndPages();
            $lookup = [];
            foreach ($contentList as $c) {
                $lookup[$c['id']] = $c;
            }

            foreach ($_POST['content_ids'] as $cid) {
                if (isset($lookup[$cid])) {
                    $item = $lookup[$cid];
                    
                    $url = '/' . $item['slug'];
                    if ($item['type'] === 'post') {
                        $url = '/posts/' . $item['slug'];
                    }

                    $this->repo->addItem($menuId, [
                        'title' => $item['title'],
                        'url' => $url,
                        'parent_id' => 0,
                        'order_index' => 0
                    ]);
                }
            }
        }

        Flash::set('success', 'Items added to menu.');
        return Redirect::back("/admin/appearance/menus/{$id}");
    }

    public function destroyItem(Request $request, string $id, string $itemId): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $this->repo->deleteItem((int)$itemId);
        Flash::set('success', 'Menu item removed.');
        return Redirect::back("/admin/appearance/menus/{$id}");
    }
}
