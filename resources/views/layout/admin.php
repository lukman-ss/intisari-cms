<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName ?? 'Intisari CMS') ?> — Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f4f6f8; color: #222; min-height: 100vh; display: flex; flex-direction: column; }
        .nav { background: #1a1a2e; color: #fff; padding: 0 1.5rem; display: flex; align-items: center; gap: 1.5rem; height: 52px; }
        .nav a { color: #ccc; text-decoration: none; font-size: 0.9rem; }
        .nav a:hover { color: #fff; }
        .nav .brand { font-weight: bold; color: #fff; font-size: 1.1rem; margin-right: auto; }
        .main { display: flex; flex: 1; }
        .sidebar { width: 200px; background: #16213e; color: #aaa; padding: 1.5rem 0; flex-shrink: 0; }
        .sidebar a { display: block; padding: 0.5rem 1.5rem; color: #aaa; text-decoration: none; font-size: 0.9rem; }
        .sidebar a:hover { background: #0f3460; color: #fff; }
        .sidebar .section { padding: 0.5rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #555; margin-top: 1rem; }
        .content { flex: 1; padding: 2rem; }
        .card { background: #fff; border-radius: 6px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 1.5rem; }
        h1 { font-size: 1.4rem; margin-bottom: 1rem; color: #1a1a2e; }
        h2 { font-size: 1.1rem; margin-bottom: 0.75rem; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { text-align: left; padding: 0.5rem 0.75rem; border-bottom: 1px solid #eee; }
        th { background: #f9fafb; font-weight: 600; color: #555; }
        .btn { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 4px; font-size: 0.85rem; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #0f3460; color: #fff; }
        .btn-danger { background: #c0392b; color: #fff; }
        .btn-sm { padding: 0.25rem 0.6rem; font-size: 0.8rem; }
        .alert { padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        form input, form textarea, form select { width: 100%; padding: 0.4rem 0.6rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; margin-top: 0.25rem; }
        form label { display: block; font-size: 0.85rem; font-weight: 600; margin-top: 0.75rem; color: #444; }
        form .form-group { margin-bottom: 0.5rem; }
        .stats { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { flex: 1; background: #fff; padding: 1.25rem; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,.08); text-align: center; }
        .stat-card .num { font-size: 2rem; font-weight: bold; color: #0f3460; }
        .stat-card .label { font-size: 0.85rem; color: #888; margin-top: 0.25rem; }
    </style>
</head>
<body>
<nav class="nav">
    <span class="brand"><?= htmlspecialchars($appName ?? 'Intisari CMS') ?></span>
    <?php if (!empty($authUser)): ?>
        <span style="font-size:.85rem;color:#aaa"><?= htmlspecialchars($authUser['name']) ?></span>
        <form action="/admin/logout" method="POST" style="margin:0">
            <button type="submit" class="btn" style="background:none;color:#ccc;padding:0;cursor:pointer;font-size:.85rem">Logout</button>
        </form>
    <?php endif; ?>
</nav>
<div class="main">
    <aside class="sidebar">
        <div class="section">Content</div>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/pages">Pages</a>
        <a href="/admin/posts">Posts</a>
        <a href="/admin/media">Media</a>
        <div class="section">System</div>
        <a href="/admin/users">Users</a>
        <a href="/admin/settings">Settings</a>
    </aside>
    <main class="content">
        <?php echo $section('slot'); ?>
    </main>
</div>
</body>
</html>
