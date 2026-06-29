<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit User</h1>
    <form method="POST" action="/admin/users/<?= $user['id'] ?>" class="box" style="max-width: 600px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Username (cannot be changed)</label>
            <input type="text" name="username" value="<?= \App\Support\View::escape($user['username']) ?>" style="width:100%; padding:8px;" readonly>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Email (required)</label>
            <input type="email" name="email" value="<?= \App\Support\View::escape($user['email']) ?>" style="width:100%; padding:8px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Name</label>
            <input type="text" name="name" style="width:100%; padding:8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">New Password</label>
            <input type="password" name="password" style="width:100%; padding:8px;">
            <span style="font-size:12px; color:#666;">If you would like to change the password type a new one. Otherwise leave this blank.</span>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Role</label>
            <select name="role" style="width:100%; padding:8px;">
                <option value="administrator">Administrator</option>
                <option value="subscriber">Subscriber</option>
            </select>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Status</label>
            <select name="status" style="width:100%; padding:8px;">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update User</button>
    </form>
</div>
