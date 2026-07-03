<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Redirect</h1>
    
    <?php if ($msg = \App\Support\Flash::get('success')): ?>
        <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border-left:4px solid #c3e6cb;">
            <?= \App\Support\View::escape($msg) ?>
        </div>
    <?php endif; ?>

    <?php if ($msg = \App\Support\Flash::get('error')): ?>
        <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-left:4px solid #f5c6cb;">
            <?= \App\Support\View::escape($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/redirects/<?= $redirect->id ?>" class="box" style="max-width: 600px; margin-top: 20px; padding: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Source URL (Old URL)</label>
            <input type="text" name="source_url" value="<?= \App\Support\View::escape($redirect->source_url) ?>" style="width:100%; padding:8px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Target URL (New URL)</label>
            <input type="text" name="target_url" value="<?= \App\Support\View::escape($redirect->target_url) ?>" style="width:100%; padding:8px;" required>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Redirect Type</label>
            <select name="type" style="width:100%; padding:8px;">
                <option value="301" <?= $redirect->type === 301 ? 'selected' : '' ?>>301 Moved Permanently</option>
                <option value="302" <?= $redirect->type === 302 ? 'selected' : '' ?>>302 Found</option>
                <option value="307" <?= $redirect->type === 307 ? 'selected' : '' ?>>307 Temporary Redirect</option>
            </select>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update Redirect</button>
        <a href="/admin/redirects" style="margin-left:10px; text-decoration:none; color:#0073aa;">Back to list</a>
    </form>
</div>
