<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>My Profile</h1>

    <form method="POST" action="/admin/profile" class="box" style="max-width:600px; margin-top:20px; padding:20px;">
        <?= \App\Support\Csrf::field() ?>

        <div style="display:flex; gap:20px; margin-bottom:20px; align-items:center;">
            <div style="width:80px; height:80px; background:#0073aa; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:28px; font-weight:bold; flex-shrink:0;">
                <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <div style="font-size:20px; font-weight:bold;"><?= \App\Support\View::escape($user['username'] ?? '') ?></div>
                <div style="color:#888; font-size:13px;"><?= \App\Support\View::escape($user['email'] ?? '') ?></div>
                <div style="color:#888; font-size:12px; margin-top:4px;">
                    Role: <strong><?= \App\Support\View::escape(ucfirst($user['role'] ?? 'unknown')) ?></strong>
                    &bull; Member since <?= substr($user['created_at'] ?? '', 0, 10) ?>
                </div>
            </div>
        </div>

        <hr style="border:none; border-top:1px solid #eee; margin-bottom:20px;">

        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Username <span style="color:#a00;">*</span></label>
            <input type="text" name="username"
                value="<?= \App\Support\View::escape($user['username'] ?? '') ?>"
                style="width:100%; padding:8px; box-sizing:border-box;" required>
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Email Address <span style="color:#a00;">*</span></label>
            <input type="email" name="email"
                value="<?= \App\Support\View::escape($user['email'] ?? '') ?>"
                style="width:100%; padding:8px; box-sizing:border-box;" required>
        </div>

        <hr style="border:none; border-top:1px solid #eee; margin: 20px 0;">
        <p style="color:#555; font-size:13px; margin-bottom:15px;">
            Leave password fields blank to keep your current password.
        </p>

        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">New Password</label>
            <input type="password" name="password"
                style="width:100%; padding:8px; box-sizing:border-box;" autocomplete="new-password"
                placeholder="Min. 6 characters">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Confirm New Password</label>
            <input type="password" name="password_confirm"
                style="width:100%; padding:8px; box-sizing:border-box;" autocomplete="new-password"
                placeholder="Repeat new password">
        </div>

        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 24px; cursor:pointer; border-radius:3px; font-size:14px;">
            Save Changes
        </button>
    </form>
</div>
