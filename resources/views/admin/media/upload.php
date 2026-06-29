<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Upload New Media</h1>
    
    <div class="box" style="margin-top: 20px; padding: 20px;">
        <form method="POST" action="/admin/media" enctype="multipart/form-data">
            <?= \App\Support\Csrf::field() ?>
            
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:10px; font-weight:bold;">Select File:</label>
                <input type="file" name="file" required>
            </div>
            
            <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Upload File</button>
        </form>
        <p style="margin-top:15px; color:#666; font-size:13px;">Maximum upload file size: 10 MB.</p>
    </div>
</div>
