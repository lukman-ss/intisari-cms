<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Roles</h1>
    <table class="box" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px;">Role Name</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($roles)): ?>
                <tr><td colspan="2" style="padding: 10px;">No roles found. Run seeders.</td></tr>
            <?php else: ?>
                <?php foreach ($roles as $role): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        <strong><?= \App\Support\View::escape(ucfirst($role['name'])) ?></strong>
                    </td>
                    <td style="padding: 10px;">
                        <?php if ($role['name'] !== 'administrator'): ?>
                            <a href="/admin/roles/<?= $role['id'] ?>/edit" style="color: #0073aa;">Edit Capabilities</a>
                        <?php else: ?>
                            <span style="color: #666;">Full Access</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
