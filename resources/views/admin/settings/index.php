<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Settings</h1>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div>
<?php endif; ?>
<div class="card">
    <?php if (empty($settings)): ?>
        <p style="color:#888">No settings configured yet.</p>
    <?php else: ?>
        <form action="/admin/settings" method="POST">
            <table>
                <thead><tr><th>Group</th><th>Key</th><th>Value</th></tr></thead>
                <tbody>
                <?php foreach ($settings as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['group'] ?? 'general') ?></td>
                        <td><?= htmlspecialchars($s['key']) ?></td>
                        <td><input type="text" name="settings[<?= htmlspecialchars($s['key']) ?>]" value="<?= htmlspecialchars($s['value'] ?? '') ?>" style="min-width:200px"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top:1rem">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php $end(); ?>
