<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Edit Page: <?= htmlspecialchars($page['title'] ?? '') ?></h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
<?php endif; ?>
<div class="card">
    <form action="/admin/pages/<?= (int) $page['id'] ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="8"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary">Update Page</button>
            &nbsp;<a href="/admin/pages">Cancel</a>
        </div>
    </form>
</div>
<?php $end(); ?>
