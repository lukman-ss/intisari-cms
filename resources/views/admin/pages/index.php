<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Pages</h1>
<p style="margin-bottom:1rem"><a href="/admin/pages/create" class="btn btn-primary btn-sm">+ New Page</a></p>
<div class="card">
    <?php if (empty($pages)): ?>
        <p style="color:#888">No pages yet.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($pages as $p): ?>
                <tr>
                    <td><?= (int) $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['title']) ?></td>
                    <td><?= htmlspecialchars($p['slug']) ?></td>
                    <td><?= htmlspecialchars($p['status'] ?? 'draft') ?></td>
                    <td><?= htmlspecialchars($p['created_at'] ?? '') ?></td>
                    <td>
                        <a href="/admin/pages/<?= (int) $p['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form action="/admin/pages/<?= (int) $p['id'] ?>/delete" method="POST" style="display:inline">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php $end(); ?>
