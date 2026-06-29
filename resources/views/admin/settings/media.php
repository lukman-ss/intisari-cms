<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Media Settings</h1>
    
    <form method="POST" action="/admin/settings/media" class="box" style="padding: 20px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <h2 style="font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Image sizes</h2>
        <p style="margin-bottom: 20px;">The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library.</p>
        
        <table class="form-table" style="width: 100%; border-spacing: 0;">
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Thumbnail size</label></th>
                <td style="padding: 15px 0;">
                    Width: <input type="number" name="thumbnail_size_w" value="<?= \App\Support\View::escape($options['thumbnail_size_w'] ?? '150') ?>" style="width: 70px; padding: 4px; margin-right: 15px;"> 
                    Height: <input type="number" name="thumbnail_size_h" value="<?= \App\Support\View::escape($options['thumbnail_size_h'] ?? '150') ?>" style="width: 70px; padding: 4px;">
                </td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Medium size</label></th>
                <td style="padding: 15px 0;">
                    Max Width: <input type="number" name="medium_size_w" value="<?= \App\Support\View::escape($options['medium_size_w'] ?? '300') ?>" style="width: 70px; padding: 4px; margin-right: 15px;"> 
                    Max Height: <input type="number" name="medium_size_h" value="<?= \App\Support\View::escape($options['medium_size_h'] ?? '300') ?>" style="width: 70px; padding: 4px;">
                </td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Large size</label></th>
                <td style="padding: 15px 0;">
                    Max Width: <input type="number" name="large_size_w" value="<?= \App\Support\View::escape($options['large_size_w'] ?? '1024') ?>" style="width: 70px; padding: 4px; margin-right: 15px;"> 
                    Max Height: <input type="number" name="large_size_h" value="<?= \App\Support\View::escape($options['large_size_h'] ?? '1024') ?>" style="width: 70px; padding: 4px;">
                </td>
            </tr>
        </table>
        
        <p class="submit" style="margin-top: 20px;"><button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">Save Changes</button></p>
    </form>
</div>
