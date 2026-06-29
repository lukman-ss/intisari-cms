<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Support\View::escape($title ?? 'Admin Dashboard') ?> - Intisari CMS</title>
    <link rel="stylesheet" href="/assets/admin/css/admin.css">
</head>
<body>
    <aside id="admin-sidebar">
        <ul>
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="/admin/posts">Posts</a></li>
            <li><a href="/admin/media">Media</a></li>
            <li><a href="/admin/pages">Pages</a></li>
            <li><a href="/admin/comments">Comments</a></li>
            <li><a href="/admin/appearance">Appearance</a></li>
            <li><a href="/admin/plugins">Plugins</a></li>
            <li><a href="/admin/users">Users</a></li>
            <li><a href="/admin/tools">Tools</a></li>
            <li><a href="/admin/settings">Settings</a></li>
        </ul>
    </aside>
    
    <div id="admin-main">
        <header id="admin-topbar">
            <div class="site-name">
                <a href="/" target="_blank">Intisari CMS</a>
            </div>
            <div class="user-menu">
                <a href="/admin/profile">Howdy, Admin</a> | <a href="/logout">Log Out</a>
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
