<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Create User</h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
<?php endif; ?>
<div class="card">
    <form action="/admin/users" method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary">Create User</button>
            &nbsp;<a href="/admin/users">Cancel</a>
        </div>
    </form>
</div>
<?php $end(); ?>
