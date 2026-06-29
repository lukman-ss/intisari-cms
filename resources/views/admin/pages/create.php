<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Create Page</h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
<?php endif; ?>
<div class="card">
    <form action="/admin/pages" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="8"></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary">Create Page</button>
            &nbsp;<a href="/admin/pages">Cancel</a>
        </div>
    </form>
</div>
<?php $end(); ?>
