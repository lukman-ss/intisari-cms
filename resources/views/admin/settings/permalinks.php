<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Permalink Settings</h1>
    
    <form method="POST" action="/admin/settings/permalinks" class="box" style="padding: 20px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <table class="form-table" style="width: 100%; border-spacing: 0;">
            <tr>
                <th style="text-align:left; padding: 15px 0; vertical-align:top;"><label>Common Settings</label></th>
                <td style="padding: 15px 0;">
                    <label style="display:block; margin-bottom:15px;">
                        <input type="radio" name="permalink_structure" value="plain" <?= ($options['permalink_structure'] ?? 'post_name') === 'plain' ? 'checked' : '' ?>>
                        Plain 
                        <code style="background:#eee; padding:2px 4px; border-radius:3px; margin-left: 10px;">/posts?id=123</code>
                    </label>
                    <label style="display:block; margin-bottom:15px;">
                        <input type="radio" name="permalink_structure" value="post_name" <?= ($options['permalink_structure'] ?? 'post_name') === 'post_name' ? 'checked' : '' ?>>
                        Post name 
                        <code style="background:#eee; padding:2px 4px; border-radius:3px; margin-left: 10px;">/posts/sample-post</code>
                    </label>
                </td>
            </tr>
        </table>
        
        <p class="submit" style="margin-top: 20px;"><button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">Save Changes</button></p>
    </form>
</div>
