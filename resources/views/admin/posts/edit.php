<?php $extend('layout.admin'); ?>
<?php $start('slot'); ?>
<h1>Edit Post: <?= htmlspecialchars($post['title'] ?? '') ?></h1>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
<?php endif; ?>
<div class="card">
    <form action="/admin/posts/<?= (int) $post['id'] ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($post['slug'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary">Update Post</button>
            &nbsp;<a href="/admin/posts">Cancel</a>
        </div>
    </form>
</div>
<?php $end(); ?>
