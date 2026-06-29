<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Reading Settings</h1>
    
    <form method="POST" action="/admin/settings/reading" class="box" style="padding: 20px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <table class="form-table" style="width: 100%; border-spacing: 0;">
            <tr>
                <th style="text-align:left; padding: 15px 0; vertical-align:top;"><label>Your homepage displays</label></th>
                <td style="padding: 15px 0;">
                    <label style="display:block; margin-bottom:10px;">
                        <input type="radio" name="homepage_mode" value="posts" <?= ($options['homepage_mode'] ?? 'posts') === 'posts' ? 'checked' : '' ?>>
                        Your latest posts
                    </label>
                    <label style="display:block; margin-bottom:10px;">
                        <input type="radio" name="homepage_mode" value="page" <?= ($options['homepage_mode'] ?? '') === 'page' ? 'checked' : '' ?>>
                        A static page (select below)
                    </label>
                    <ul style="list-style:none; margin-left: 20px; padding:0;">
                        <li style="margin-bottom: 10px;">
                            <label style="display:inline-block; width:100px;">Homepage:</label>
                            <select name="homepage_page_id" style="padding: 4px;">
                                <option value="0">&mdash; Select &mdash;</option>
                                <?php foreach ($pages as $page): ?>
                                    <option value="<?= $page['id'] ?>" <?= (int)($options['homepage_page_id'] ?? 0) === $page['id'] ? 'selected' : '' ?>><?= \App\Support\View::escape($page['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <label style="display:inline-block; width:100px;">Posts page:</label>
                            <select name="posts_page_id" style="padding: 4px;">
                                <option value="0">&mdash; Select &mdash;</option>
                                <?php foreach ($pages as $page): ?>
                                    <option value="<?= $page['id'] ?>" <?= (int)($options['posts_page_id'] ?? 0) === $page['id'] ? 'selected' : '' ?>><?= \App\Support\View::escape($page['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th style="text-align:left; padding: 15px 0;"><label>Blog pages show at most</label></th>
                <td style="padding: 15px 0;"><input type="number" name="posts_per_page" value="<?= \App\Support\View::escape($options['posts_per_page'] ?? '10') ?>" style="width: 60px; padding: 4px;"> posts</td>
            </tr>
        </table>
        
        <p class="submit" style="margin-top: 20px;"><button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius: 3px;">Save Changes</button></p>
    </form>
</div>
