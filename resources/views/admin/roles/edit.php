<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Role: <?= \App\Support\View::escape(ucfirst($role['name'])) ?></h1>
    
    <form method="POST" action="/admin/roles/<?= $role['id'] ?>" class="box" style="margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <p>Select capabilities for this role:</p>
        
        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            <?php foreach ($all_capabilities as $cap): ?>
                <label style="width: 200px; display: block; background: #f9f9f9; padding: 10px; border: 1px solid #ddd;">
                    <input type="checkbox" name="capabilities[]" value="<?= \App\Support\View::escape($cap) ?>"
                        <?= in_array($cap, $current_capabilities, true) ? 'checked' : '' ?>>
                    <?= \App\Support\View::escape(ucwords(str_replace('_', ' ', $cap))) ?>
                </label>
            <?php endforeach; ?>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update Role Capabilities</button>
    </form>
</div>
