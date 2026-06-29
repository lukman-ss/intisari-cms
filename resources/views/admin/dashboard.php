<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Dashboard</h1>

<div class="stats">
    <div class="stat-card">
        <div class="num"><?= (int) ($userCount ?? 0) ?></div>
        <div class="label">Users</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= (int) ($pageCount ?? 0) ?></div>
        <div class="label">Pages</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= (int) ($postCount ?? 0) ?></div>
        <div class="label">Posts</div>
    </div>
</div>

<div class="card">
    <h2>Quick Links</h2>
    <p style="margin-top:.5rem">
        <a href="/admin/pages/create" class="btn btn-primary btn-sm">New Page</a>&nbsp;
        <a href="/admin/posts/create" class="btn btn-primary btn-sm">New Post</a>&nbsp;
        <a href="/admin/users/create" class="btn btn-primary btn-sm">New User</a>
    </p>
</div>
<?php $end(); ?>
