<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Page</h1>
    <form method="POST" action="/admin/pages/<?= $page->id ?>" style="margin-top: 20px; display: flex; gap: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="flex: 1;">
            <div style="margin-bottom: 15px;">
                <input type="text" name="title" value="<?= \App\Support\View::escape($page->title) ?>" style="width:100%; padding:10px; font-size: 24px;" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Slug:</label>
                <input type="text" name="slug" value="<?= \App\Support\View::escape($page->slug) ?>" style="width:100%; padding:5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <textarea name="content" style="width:100%; padding:10px; height: 400px; font-family: monospace;"><?= \App\Support\View::escape($page->content ?? '') ?></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Excerpt</label>
                <textarea name="excerpt" style="width:100%; padding:10px; height: 100px;"><?= \App\Support\View::escape($page->excerpt ?? '') ?></textarea>
            </div>
        </div>
        
        <div style="width: 280px;">
            <div class="box" style="margin-bottom: 20px;">
                <h3 style="margin-top:0;">Publish</h3>
                <div style="margin-bottom: 15px;">
                    <label>Status:</label>
                    <select name="status" style="width:100%; padding:5px; margin-top:5px;">
                        <option value="draft" <?= $page->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $page->status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div style="text-align: right;">
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Update Page</button>
                </div>
            </div>

            <div class="box" style="margin-bottom: 20px;">
                <h3 style="margin-top:0;">Page Attributes</h3>
                <div style="margin-bottom: 15px;">
                    <label>Parent Page:</label>
                    <select name="parent_id" style="width:100%; padding:5px; margin-top:5px;">
                        <option value="0">(no parent)</option>
                        <?php foreach ($allPages as $p): ?>
                            <?php if ($p->id !== $page->id): ?>
                                <option value="<?= $p->id ?>" <?= (int)$page->parent_id === (int)$p->id ? 'selected' : '' ?>>
                                    <?= \App\Support\View::escape($p->title) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Order:</label>
                    <input type="number" name="menu_order" value="<?= \App\Support\View::escape((string)$page->menu_order) ?>" style="width:100%; padding:5px; margin-top:5px;">
                </div>
            </div>
        </div>
    </form>
</div>
