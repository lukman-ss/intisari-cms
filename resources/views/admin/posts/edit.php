<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Post</h1>
    <form method="POST" action="/admin/posts/<?= $post->id ?>" style="margin-top: 20px; display: flex; gap: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="flex: 1;">
            <div style="margin-bottom: 15px;">
                <input type="text" name="title" value="<?= \App\Support\View::escape($post->title) ?>" style="width:100%; padding:10px; font-size: 24px;" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Slug:</label>
                <input type="text" name="slug" value="<?= \App\Support\View::escape($post->slug) ?>" style="width:100%; padding:5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <textarea name="content" style="width:100%; padding:10px; height: 400px; font-family: monospace;"><?= \App\Support\View::escape($post->content ?? '') ?></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Excerpt</label>
                <textarea name="excerpt" style="width:100%; padding:10px; height: 100px;"><?= \App\Support\View::escape($post->excerpt ?? '') ?></textarea>
            </div>
        </div>
        
        <div style="width: 280px;">
            <div class="box" style="margin-bottom: 20px;">
                <h3 style="margin-top:0;">Publish</h3>
                <div style="margin-bottom: 15px;">
                    <label>Status:</label>
                    <select name="status" style="width:100%; padding:5px; margin-top:5px;">
                        <option value="draft" <?= $post->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $post->status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div style="text-align: right;">
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Update Post</button>
                </div>
            </div>
        </div>
    </form>
</div>
