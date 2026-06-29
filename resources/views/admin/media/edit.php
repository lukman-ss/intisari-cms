<?php declare(strict_types=1); ?>
<div class="wrap" style="display:flex; gap: 30px;">
    <div style="flex:1;">
        <h1>Edit Media</h1>
        <form method="POST" action="/admin/media/<?= $media['id'] ?>" style="margin-top: 20px;">
            <?= \App\Support\Csrf::field() ?>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Title</label>
                <input type="text" name="title" value="<?= \App\Support\View::escape($media['metadata_decoded']['title'] ?? '') ?>" style="width:100%; padding:8px;" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Alternative Text</label>
                <input type="text" name="alt" value="<?= \App\Support\View::escape($media['metadata_decoded']['alt'] ?? '') ?>" style="width:100%; padding:8px;">
                <p style="font-size:12px; color:#666; margin-top:5px;">Describe the purpose of the image. Leave empty if the image is purely decorative.</p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Caption</label>
                <textarea name="caption" rows="2" style="width:100%; padding:8px;"><?= \App\Support\View::escape($media['metadata_decoded']['caption'] ?? '') ?></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Description</label>
                <textarea name="description" rows="5" style="width:100%; padding:8px;"><?= \App\Support\View::escape($media['metadata_decoded']['description'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update Media</button>
        </form>
    </div>
    
    <div style="width:300px; margin-top:20px;">
        <div class="box" style="padding: 15px;">
            <h3 style="margin-top:0;">File Info</h3>
            
            <?php if (str_starts_with($media['mime_type'], 'image/')): ?>
                <div style="margin-bottom: 15px;">
                    <img src="/storage/uploads/<?= \App\Support\View::escape($media['filename']) ?>" style="max-width:100%; height:auto;">
                </div>
            <?php endif; ?>
            
            <p><strong>Uploaded on:</strong> <?= \App\Support\View::escape($media['created_at']) ?></p>
            <p><strong>File name:</strong> <?= \App\Support\View::escape($media['filename']) ?></p>
            <p><strong>File type:</strong> <?= \App\Support\View::escape($media['mime_type']) ?></p>
            <p><strong>File size:</strong> <?= number_format((float)$media['size'] / 1024, 2) ?> KB</p>
            
            <p style="margin-top:15px; border-top:1px solid #ddd; padding-top:15px;">
                <strong>File URL:</strong><br>
                <input type="text" value="/storage/uploads/<?= \App\Support\View::escape($media['filename']) ?>" readonly style="width:100%; padding:5px; margin-top:5px; background:#f9f9f9;">
            </p>
        </div>
    </div>
</div>
