<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>API Tokens</h1>
    
    <?php if ($newPlainTextToken): ?>
        <div class="notice notice-success" style="padding: 15px; margin: 20px 0; background: #fff; border-left: 4px solid #46b450; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
            <p><strong>Please copy your new API token. For your security, it won't be shown again.</strong></p>
            <p style="background: #f0f0f1; padding: 10px; font-family: monospace; font-size: 16px; word-break: break-all;">
                <?= \App\Support\View::escape($newPlainTextToken) ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="box" style="padding: 20px; background: #fff; margin-top: 20px;">
        <h2>Create New Token</h2>
        <form method="POST" action="/admin/tools/api-tokens">
            <?= \App\Support\Csrf::field() ?>
            <p>
                <label>Token Name</label><br>
                <input type="text" name="name" required style="width: 300px; padding: 5px;">
            </p>
            <button type="submit" class="button button-primary">Create Token</button>
        </form>
    </div>

    <h2 style="margin-top: 30px;">Active Tokens</h2>
    <table class="wp-list-table widefat fixed striped" style="width:100%; border-collapse:collapse; background:#fff; margin-top:10px;">
        <thead>
            <tr>
                <th style="text-align:left; padding:10px; border-bottom:1px solid #ccd0d4;">Name</th>
                <th style="text-align:left; padding:10px; border-bottom:1px solid #ccd0d4;">Created At</th>
                <th style="text-align:left; padding:10px; border-bottom:1px solid #ccd0d4;">Last Used</th>
                <th style="text-align:right; padding:10px; border-bottom:1px solid #ccd0d4;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tokens)): ?>
                <tr>
                    <td colspan="4" style="padding:15px; text-align:center;">No tokens found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tokens as $token): ?>
                    <tr>
                        <td style="padding:10px; border-bottom:1px solid #eee;"><strong><?= \App\Support\View::escape($token['name']) ?></strong></td>
                        <td style="padding:10px; border-bottom:1px solid #eee;"><?= \App\Support\View::escape($token['created_at']) ?></td>
                        <td style="padding:10px; border-bottom:1px solid #eee;"><?= \App\Support\View::escape($token['last_used_at'] ?? 'Never') ?></td>
                        <td style="padding:10px; border-bottom:1px solid #eee; text-align:right;">
                            <form method="POST" action="/admin/tools/api-tokens/<?= (int)$token['id'] ?>/revoke" style="margin:0;" onsubmit="return confirm('Are you sure you want to revoke this token?');">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="color:#a00; background:none; border:none; cursor:pointer;">Revoke</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
