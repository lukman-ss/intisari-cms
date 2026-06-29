<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Edit User: <?= htmlspecialchars($editUser['name'] ?? '') ?></h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
<?php endif; ?>
<div class="card">
    <form action="/admin/users/<?= (int) $editUser['id'] ?>" method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($editUser['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($editUser['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="password">New Password <small style="font-weight:normal;color:#888">(leave blank to keep current)</small></label>
            <input type="password" id="password" name="password">
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary">Update User</button>
            &nbsp;<a href="/admin/users">Cancel</a>
        </div>
    </form>
</div>
<?php $end(); ?>
