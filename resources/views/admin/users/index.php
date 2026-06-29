<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Users</h1>
<p style="margin-bottom:1rem"><a href="/admin/users/create" class="btn btn-primary btn-sm">+ New User</a></p>
<div class="card">
    <?php if (empty($users)): ?>
        <p style="color:#888">No users yet.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int) $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['created_at'] ?? '') ?></td>
                    <td>
                        <a href="/admin/users/<?= (int) $u['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form action="/admin/users/<?= (int) $u['id'] ?>/delete" method="POST" style="display:inline">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php $end(); ?>
