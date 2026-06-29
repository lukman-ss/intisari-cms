<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Plugins</h1>
    
    <table class="wp-list-table widefat plugins" style="width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0.04);">
        <thead>
            <tr>
                <th style="padding: 10px; border-bottom: 1px solid #ccd0d4; text-align: left; width: 30%;">Plugin</th>
                <th style="padding: 10px; border-bottom: 1px solid #ccd0d4; text-align: left;">Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($plugins)): ?>
                <tr>
                    <td colspan="2" style="padding: 15px; text-align: center;">No plugins installed.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($plugins as $plugin): ?>
                    <?php 
                        $isActive = in_array($plugin->slug, $activePlugins); 
                        $bg = $isActive ? '#f3f6f8' : '#fff';
                        $border = $isActive ? 'border-left: 4px solid #00a0d2;' : 'border-left: 4px solid transparent;';
                    ?>
                    <tr style="background: <?= $bg ?>; <?= $border ?>">
                        <td style="padding: 15px; border-bottom: 1px solid #eee; vertical-align: top;">
                            <strong><?= \App\Support\View::escape($plugin->name) ?></strong>
                            <div style="margin-top: 5px; font-size: 13px;">
                                <?php if ($isActive): ?>
                                    <form method="POST" action="/admin/plugins/<?= urlencode($plugin->slug) ?>/deactivate" style="display:inline;">
                                        <?= \App\Support\Csrf::field() ?>
                                        <button type="submit" style="background:none; border:none; color:#a00; cursor:pointer; padding:0; text-decoration:none;">Deactivate</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="/admin/plugins/<?= urlencode($plugin->slug) ?>/activate" style="display:inline;">
                                        <?= \App\Support\Csrf::field() ?>
                                        <button type="submit" style="background:none; border:none; color:#0073aa; cursor:pointer; padding:0; text-decoration:none;">Activate</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 15px; border-bottom: 1px solid #eee; vertical-align: top; font-size: 13px;">
                            <p style="margin: 0 0 10px 0;"><?= \App\Support\View::escape($plugin->description) ?></p>
                            <p style="margin: 0; color: #666;">
                                Version <?= \App\Support\View::escape($plugin->version) ?> | 
                                By <?= \App\Support\View::escape($plugin->author) ?>
                            </p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
