<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Add New Page</h1>
    <form method="POST" action="/admin/pages" style="margin-top: 20px; display: flex; gap: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="flex: 1;">
            <div style="margin-bottom: 15px;">
                <input type="text" name="title" placeholder="Add title" style="width:100%; padding:10px; font-size: 24px;" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <textarea name="content" style="width:100%; padding:10px; height: 400px; font-family: monospace;" placeholder="Page content..."></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Excerpt</label>
                <textarea name="excerpt" style="width:100%; padding:10px; height: 100px;"></textarea>
            </div>
        </div>
        
        <div style="width: 280px;">
            <div class="box" style="margin-bottom: 20px;">
                <h3 style="margin-top:0;">Publish</h3>
                <div style="margin-bottom: 15px;">
                    <label>Status:</label>
                    <select name="status" style="width:100%; padding:5px; margin-top:5px;">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <div style="text-align: right;">
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Save Page</button>
                </div>
            </div>

            <div class="box" style="margin-bottom: 20px;">
                <h3 style="margin-top:0;">Page Attributes</h3>
                <div style="margin-bottom: 15px;">
                    <label>Parent Page:</label>
                    <select name="parent_id" style="width:100%; padding:5px; margin-top:5px;">
                        <option value="0">(no parent)</option>
                        <?php foreach ($allPages as $p): ?>
                            <option value="<?= $p->id ?>"><?= \App\Support\View::escape($p->title) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Order:</label>
                    <input type="number" name="menu_order" value="0" style="width:100%; padding:5px; margin-top:5px;">
                </div>
            </div>
        </div>
    </form>
</div>
