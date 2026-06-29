<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>General Settings</h1>
    
    <form method="POST" action="/admin/settings/general" class="box" style="padding: 20px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <table class="form-table" style="width: 100%; border-spacing: 0;">
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Site Title</label></th>
                <td style="padding: 15px 0;"><input type="text" name="site_title" value="<?= \App\Support\View::escape($options['site_title'] ?? '') ?>" style="width: 350px; padding: 6px;"></td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Tagline</label></th>
                <td style="padding: 15px 0;">
                    <input type="text" name="tagline" value="<?= \App\Support\View::escape($options['tagline'] ?? '') ?>" style="width: 350px; padding: 6px;">
                    <p style="font-size: 13px; color: #666; margin-top: 5px;">In a few words, explain what this site is about.</p>
                </td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Timezone</label></th>
                <td style="padding: 15px 0;"><input type="text" name="timezone" value="<?= \App\Support\View::escape($options['timezone'] ?? 'UTC') ?>" style="width: 200px; padding: 6px;"></td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Locale</label></th>
                <td style="padding: 15px 0;"><input type="text" name="locale" value="<?= \App\Support\View::escape($options['locale'] ?? 'en_US') ?>" style="width: 100px; padding: 6px;"></td>
            </tr>
        </table>
        
        <p class="submit" style="margin-top: 20px;"><button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">Save Changes</button></p>
    </form>
</div>
