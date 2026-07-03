<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Add New Redirect</h1>
    
    <?php if ($msg = \App\Support\Flash::get('error')): ?>
        <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-left:4px solid #f5c6cb;">
            <?= \App\Support\View::escape($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/redirects" class="box" style="max-width: 600px; margin-top: 20px; padding: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Source URL (Old URL)</label>
            <input type="text" name="source_url" style="width:100%; padding:8px;" placeholder="e.g. /old-page or /category/old" required>
            <small style="color:#666;">The relative path that is no longer active.</small>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Target URL (New URL)</label>
            <input type="text" name="target_url" style="width:100%; padding:8px;" placeholder="e.g. /new-page or https://external.com" required>
            <small style="color:#666;">Where the user should be redirected.</small>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Redirect Type</label>
            <select name="type" style="width:100%; padding:8px;">
                <option value="301">301 Moved Permanently (SEO Friendly)</option>
                <option value="302">302 Found (Temporary)</option>
                <option value="307">307 Temporary Redirect</option>
            </select>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Create Redirect</button>
        <a href="/admin/redirects" style="margin-left:10px; text-decoration:none; color:#0073aa;">Cancel</a>
    </form>
</div>
