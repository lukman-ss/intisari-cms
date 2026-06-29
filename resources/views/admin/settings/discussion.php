<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Discussion Settings</h1>
    
    <form method="POST" action="/admin/settings/discussion" class="box" style="padding: 20px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <table class="form-table" style="width: 100%; border-spacing: 0;">
            <tr>
                <th style="text-align:left; padding: 15px 0; vertical-align:top;"><label>Before a comment appears</label></th>
                <td style="padding: 15px 0;">
                    <label>
                        <input type="checkbox" name="comment_moderation" value="1" <?= ($options['comment_moderation'] ?? '1') === '1' ? 'checked' : '' ?>>
                        Comment must be manually approved
                    </label>
                </td>
            </tr>
        </table>
        
        <p class="submit" style="margin-top: 20px;"><button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">Save Changes</button></p>
    </form>
</div>
