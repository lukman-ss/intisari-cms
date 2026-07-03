<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit User</h1>
    <form method="POST" action="/admin/users/<?= $user['id'] ?>" class="box" style="max-width: 600px; margin-top: 20px; padding:20px;">
        <?= \App\Support\Csrf::field() ?>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Username <span style="color:#a00;">*</span></label>
            <input type="text" name="username" value="<?= \App\Support\View::escape($user['username']) ?>"
                style="width:100%; padding:8px; box-sizing:border-box;" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Email <span style="color:#a00;">*</span></label>
            <input type="email" name="email" value="<?= \App\Support\View::escape($user['email']) ?>"
                style="width:100%; padding:8px; box-sizing:border-box;" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">New Password</label>
            <input type="password" name="password" style="width:100%; padding:8px; box-sizing:border-box;">
            <span style="font-size:12px; color:#666;">Leave blank to keep current password.</span>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Role</label>
            <select name="role" style="width:100%; padding:8px; box-sizing:border-box;">
                <?php foreach ($roles ?? [] as $r): ?>
                    <option value="<?= \App\Support\View::escape($r['name']) ?>"
                        <?= ($user['role'] ?? '') === $r['name'] ? 'selected' : '' ?>>
                        <?= \App\Support\View::escape(ucfirst($r['name'])) ?>
                    </option>
                <?php endforeach; ?>
                <?php if (empty($roles)): ?>
                    <option value="administrator" <?= ($user['role'] ?? '') === 'administrator' ? 'selected' : '' ?>>Administrator</option>
                    <option value="editor" <?= ($user['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="author" <?= ($user['role'] ?? '') === 'author' ? 'selected' : '' ?>>Author</option>
                    <option value="contributor" <?= ($user['role'] ?? '') === 'contributor' ? 'selected' : '' ?>>Contributor</option>
                    <option value="subscriber" <?= ($user['role'] ?? '') === 'subscriber' ? 'selected' : '' ?>>Subscriber</option>
                <?php endif; ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Status</label>
            <select name="status" style="width:100%; padding:8px; box-sizing:border-box;">
                <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius:3px;">
            Update User
        </button>
    </form>
</div>
