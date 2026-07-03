<?php declare(strict_types=1);
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$authUser = \App\Auth\AuthManager::guard()->user();

function isActive(string $prefix, string $current): string {
    return str_starts_with($current, $prefix) ? ' class="active"' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Support\View::escape($title ?? 'Admin Dashboard') ?> - Intisari CMS</title>
    <link rel="stylesheet" href="/assets/admin/css/admin.css">
    <style>
        #admin-sidebar .active > a { font-weight: bold; color: #fff; background: rgba(255,255,255,.15); border-radius: 3px; }
        #admin-sidebar .menu-section { font-size: 10px; text-transform: uppercase; color: rgba(255,255,255,.4); padding: 12px 15px 4px; letter-spacing: 1px; }
        #admin-sidebar ul li ul { padding-left: 10px; }
        #admin-sidebar ul li ul li a { font-size: 12px; padding: 5px 10px; }
    </style>
</head>
<body>
    <aside id="admin-sidebar">
        <div style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,.1); margin-bottom: 8px;">
            <a href="/" target="_blank" style="color: #fff; text-decoration: none; font-size: 15px; font-weight: bold;">
                ⚡ Intisari CMS
            </a>
        </div>
        <ul>
            <li<?= isActive('/admin/dashboard', $currentPath) ?>>
                <a href="/admin/dashboard">📊 Dashboard</a>
            </li>

            <div class="menu-section">Content</div>
            <li<?= isActive('/admin/posts', $currentPath) ?>>
                <a href="/admin/posts">📝 Posts</a>
            </li>
            <li<?= isActive('/admin/pages', $currentPath) ?>>
                <a href="/admin/pages">📄 Pages</a>
            </li>
            <li<?= isActive('/admin/comments', $currentPath) ?>>
                <a href="/admin/comments">💬 Comments</a>
            </li>
            <li<?= isActive('/admin/media', $currentPath) ?>>
                <a href="/admin/media">🖼️ Media</a>
            </li>

            <div class="menu-section">Taxonomy</div>
            <li<?= isActive('/admin/categories', $currentPath) ?>>
                <a href="/admin/categories">🏷️ Categories</a>
            </li>
            <li<?= isActive('/admin/tags', $currentPath) ?>>
                <a href="/admin/tags">🔖 Tags</a>
            </li>

            <div class="menu-section">Appearance</div>
            <li<?= isActive('/admin/appearance/menus', $currentPath) ?>>
                <a href="/admin/appearance/menus">🧭 Menus</a>
            </li>
            <li<?= isActive('/admin/appearance/themes', $currentPath) ?>>
                <a href="/admin/appearance/themes">🎨 Themes</a>
            </li>
            <li<?= isActive('/admin/appearance/widgets', $currentPath) ?>>
                <a href="/admin/appearance/widgets">🧩 Widgets</a>
            </li>

            <div class="menu-section">System</div>
            <li<?= isActive('/admin/plugins', $currentPath) ?>>
                <a href="/admin/plugins">🔌 Plugins</a>
            </li>
            <li<?= isActive('/admin/users', $currentPath) ?>>
                <a href="/admin/users">👥 Users</a>
            </li>
            <li<?= isActive('/admin/roles', $currentPath) ?>>
                <a href="/admin/roles">🔐 Roles</a>
            </li>
            <li<?= isActive('/admin/tools', $currentPath) ?>>
                <a href="/admin/tools">🔧 Tools</a>
            </li>
            <li<?= isActive('/admin/settings', $currentPath) ?>>
                <a href="/admin/settings/general">⚙️ Settings</a>
            </li>
        </ul>
    </aside>

    <div id="admin-main">
        <header id="admin-topbar">
            <div class="site-name">
                <a href="/" target="_blank">← View Site</a>
            </div>
            <div class="user-menu">
                <?php if ($authUser): ?>
                    <a href="/admin/profile" style="font-weight:bold;">
                        <?= \App\Support\View::escape($authUser['username'] ?? 'Admin') ?>
                    </a>
                    &nbsp;|&nbsp;
                <?php endif; ?>
                <a href="/logout">Log Out</a>
            </div>
        </header>

        <main id="admin-content-area">
            <?php if (\App\Support\Flash::has('success')): ?>
                <div class="flash-message success">
                    <?= \App\Support\View::escape((string)\App\Support\Flash::get('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (\App\Support\Flash::has('error')): ?>
                <div class="flash-message error">
                    <?= \App\Support\View::escape((string)\App\Support\Flash::get('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($content)) echo $content; ?>
        </main>

        <footer id="admin-footer">
            Thank you for creating with Intisari.
        </footer>
    </div>
</body>
</html>
