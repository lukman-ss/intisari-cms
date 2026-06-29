<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Category</h1>
    <form method="POST" action="/admin/categories/<?= $category['id'] ?>" class="box" style="max-width: 600px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Name</label>
            <input type="text" name="name" value="<?= \App\Support\View::escape($category['name']) ?>" style="width:100%; padding:8px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Slug</label>
            <input type="text" name="slug" value="<?= \App\Support\View::escape($category['slug']) ?>" style="width:100%; padding:8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:8px;"><?= \App\Support\View::escape($category['description'] ?? '') ?></textarea>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update Category</button>
    </form>
</div>
