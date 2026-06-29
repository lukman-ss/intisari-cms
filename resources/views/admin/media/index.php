<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Media</h1>
<div class="card">
    <?php if (empty($media)): ?>
        <p style="color:#888">No media records yet.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Filename</th><th>Path</th><th>Type</th><th>Size</th><th>Uploaded</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($media as $m): ?>
                <tr>
                    <td><?= (int) $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['filename']) ?></td>
                    <td><?= htmlspecialchars($m['path']) ?></td>
                    <td><?= htmlspecialchars($m['mime_type']) ?></td>
                    <td><?= number_format((int) $m['size']) ?> B</td>
                    <td><?= htmlspecialchars($m['created_at'] ?? '') ?></td>
                    <td>
                        <form action="/admin/media/<?= (int) $m['id'] ?>/delete" method="POST" style="display:inline">
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
