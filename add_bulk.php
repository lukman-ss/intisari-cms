<?php
$controllers = [
    'PostController' => [
        'path' => 'app/Controllers/Admin/PostController.php',
        'code' => <<<'PHP'

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::DELETE_POSTS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/posts');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No posts selected.');
            return \App\Support\Redirect::to('/admin/posts');
        }

        foreach ($ids as $id) {
            $post = $this->repo->find((int)$id);
            if ($post && $post->type === \App\Support\PostType::POST) {
                if ($action === 'trash') {
                    $this->repo->trash((int)$id);
                } elseif ($action === 'restore') {
                    $this->repo->update((int)$id, ['status' => \App\Support\PostStatus::DRAFT]);
                } elseif ($action === 'delete') {
                    $this->repo->delete((int)$id);
                }
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/posts');
    }
}
PHP
    ],
    'PageController' => [
        'path' => 'app/Controllers/Admin/PageController.php',
        'code' => <<<'PHP'

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::DELETE_PAGES)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/pages');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No pages selected.');
            return \App\Support\Redirect::to('/admin/pages');
        }

        foreach ($ids as $id) {
            $post = $this->repo->find((int)$id);
            if ($post && $post->type === \App\Support\PostType::PAGE) {
                if ($action === 'trash') {
                    $this->repo->trash((int)$id);
                } elseif ($action === 'restore') {
                    $this->repo->update((int)$id, ['status' => \App\Support\PostStatus::DRAFT]);
                } elseif ($action === 'delete') {
                    $this->repo->delete((int)$id);
                }
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/pages');
    }
}
PHP
    ],
    'MediaController' => [
        'path' => 'app/Controllers/Admin/MediaController.php',
        'code' => <<<'PHP'

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::UPLOAD_FILES)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/media');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No media selected.');
            return \App\Support\Redirect::to('/admin/media');
        }

        if ($action === 'delete') {
            foreach ($ids as $id) {
                $this->repo->delete((int)$id);
            }
            \App\Support\Flash::set('success', 'Bulk action completed.');
        }
        return \App\Support\Redirect::back('/admin/media');
    }
}
PHP
    ],
    'CommentController' => [
        'path' => 'app/Controllers/Admin/CommentController.php',
        'code' => <<<'PHP'

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::MODERATE_COMMENTS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/comments');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No comments selected.');
            return \App\Support\Redirect::to('/admin/comments');
        }

        foreach ($ids as $id) {
            if ($action === 'approve') {
                $this->repo->update((int)$id, ['status' => 'approved']);
            } elseif ($action === 'spam') {
                $this->repo->update((int)$id, ['status' => 'spam']);
            } elseif ($action === 'trash') {
                $this->repo->update((int)$id, ['status' => 'trash']);
            } elseif ($action === 'delete') {
                $this->repo->delete((int)$id);
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/comments');
    }
}
PHP
    ],
    'UserController' => [
        'path' => 'app/Controllers/Admin/UserController.php',
        'code' => <<<'PHP'

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::MANAGE_USERS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/users');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No users selected.');
            return \App\Support\Redirect::to('/admin/users');
        }

        if ($action === 'delete') {
            foreach ($ids as $id) {
                if ((int)$id === 1) {
                    \App\Support\Flash::set('error', 'Cannot delete the main admin user.');
                    continue;
                }
                if ((int)$id === \App\Auth\AuthManager::guard()->id()) {
                    \App\Support\Flash::set('error', 'Cannot delete yourself.');
                    continue;
                }
                $this->repo->delete((int)$id);
            }
            \App\Support\Flash::set('success', 'Bulk action completed.');
        }
        return \App\Support\Redirect::back('/admin/users');
    }
}
PHP
    ],
];

foreach ($controllers as $name => $data) {
    $content = file_get_contents($data['path']);
    if (!str_contains($content, 'function bulk')) {
        $content = preg_replace('/}\s*$/', $data['code'], $content);
        file_put_contents($data['path'], $content);
        echo "Updated $name\n";
    }
}

// Update views
$views = [
    'resources/views/admin/posts/index.php' => [
        'actions' => '<option value="trash">Move to Trash</option><option value="restore">Restore</option><option value="delete">Delete Permanently</option>',
        'url' => '/admin/posts/bulk',
        'item' => '$post->id',
    ],
    'resources/views/admin/pages/index.php' => [
        'actions' => '<option value="trash">Move to Trash</option><option value="restore">Restore</option><option value="delete">Delete Permanently</option>',
        'url' => '/admin/pages/bulk',
        'item' => '$post->id',
    ],
    'resources/views/admin/media/index.php' => [
        'actions' => '<option value="delete">Delete Permanently</option>',
        'url' => '/admin/media/bulk',
        'item' => '$media->id',
    ],
    'resources/views/admin/comments/index.php' => [
        'actions' => '<option value="approve">Approve</option><option value="spam">Mark as Spam</option><option value="trash">Move to Trash</option><option value="delete">Delete Permanently</option>',
        'url' => '/admin/comments/bulk',
        'item' => '$comment->id',
    ],
    'resources/views/admin/users/index.php' => [
        'actions' => '<option value="delete">Delete Permanently</option>',
        'url' => '/admin/users/bulk',
        'item' => '$user[\'id\'] ?? $user->id',
    ],
];

foreach ($views as $path => $data) {
    if (!file_exists($path)) {
        echo "View $path not found.\n";
        continue;
    }
    
    $content = file_get_contents($path);
    if (!str_contains($content, 'name="ids[]"')) {
        // Add form wrap around table
        $formStart = <<<HTML
    <form method="POST" action="{$data['url']}">
        <?= \App\Support\Csrf::field() ?>
        <div style="margin-bottom: 10px;">
            <select name="action">
                <option value="">Bulk Actions</option>
                {$data['actions']}
            </select>
            <button type="submit" class="button" onclick="return confirm('Are you sure you want to perform this bulk action?');">Apply</button>
        </div>
HTML;
        
        $content = preg_replace('/<table/', $formStart . "\n    <table", $content);
        $content = preg_replace('/<\/table>/', "</table>\n    </form>", $content);
        
        // Add th checkbox
        $content = preg_replace('/(<thead>\s*<tr[^>]*>\s*)(<th[^>]*>)/is', '$1<th style="padding: 10px; width: 30px;"><input type="checkbox" onclick="let checkboxes = document.querySelectorAll(\'input[name=\\\'ids[]\\\']\'); for(let cb of checkboxes) { cb.checked = this.checked; }"></th>$2', $content);
        
        // Add td checkbox
        // We find the first <tr> after <?php foreach and before </tr>
        // Replace <tr...> with <tr...> <td><input.../></td>
        $content = preg_replace('/(<tr[^>]*>\s*)(<td[^>]*>)/is', '$1<td style="padding: 10px;"><input type="checkbox" name="ids[]" value="<?= ' . $data['item'] . ' ?>"></td>$2', $content);
        
        file_put_contents($path, $content);
        echo "Updated view $path\n";
    }
}

// Update routes/web.php
$webPath = 'routes/web.php';
$webContent = file_get_contents($webPath);
$replacements = [
    '/admin/users' => "\$app->post('/admin/users/bulk', [\App\Controllers\Admin\UserController::class, 'bulk']);\n",
    '/admin/posts' => "\$app->post('/admin/posts/bulk', [\App\Controllers\Admin\PostController::class, 'bulk']);\n",
    '/admin/pages' => "\$app->post('/admin/pages/bulk', [\App\Controllers\Admin\PageController::class, 'bulk']);\n",
    '/admin/media' => "\$app->post('/admin/media/bulk', [\App\Controllers\Admin\MediaController::class, 'bulk']);\n",
    '/admin/comments' => "\$app->post('/admin/comments/bulk', [\App\Controllers\Admin\CommentController::class, 'bulk']);\n",
];

foreach ($replacements as $key => $line) {
    if (!str_contains($webContent, $line)) {
        // Insert right after the GET route for index
        $webContent = preg_replace("/(\\\$app->get\('{$key}', \[\\\\\App\\\\\Controllers\\\\\Admin\\\\\[A-Za-z]+Controller::class, 'index'\]\);)/", "$1\n$line", $webContent);
    }
}
file_put_contents($webPath, $webContent);
echo "Updated web.php\n";
