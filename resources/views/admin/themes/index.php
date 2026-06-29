<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Themes</h1>
    
    <div style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php foreach ($themes as $theme): ?>
            <?php 
                $isActive = $activeTheme && $activeTheme->slug === $theme->slug; 
            ?>
            <div class="box" style="border: <?= $isActive ? '2px solid #0073aa' : '1px solid #ccd0d4' ?>; overflow: hidden; display: flex; flex-direction: column;">
                <div style="background: #f1f1f1; height: 180px; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #eee;">
                    <span style="font-size: 24px; color: #ccc;">🖼️ <?= \App\Support\View::escape($theme->name) ?></span>
                </div>
                
                <div style="padding: 15px; flex: 1;">
                    <h3 style="margin: 0 0 10px 0; font-size: 16px;">
                        <?= \App\Support\View::escape($theme->name) ?>
                        <span style="font-weight: normal; font-size: 13px; color: #666;">Version <?= \App\Support\View::escape($theme->version) ?></span>
                    </h3>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #444;"><?= \App\Support\View::escape($theme->description) ?></p>
                    <p style="margin: 0; font-size: 13px; color: #666;">By <?= \App\Support\View::escape($theme->author) ?></p>
                </div>
                
                <div style="padding: 10px 15px; background: #fafafa; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <?php if ($isActive): ?>
                        <span style="color: #0073aa; font-weight: bold;">Active</span>
                    <?php else: ?>
                        <form method="POST" action="/admin/appearance/themes/<?= urlencode($theme->slug) ?>/activate" style="margin:0;">
                            <?= \App\Support\Csrf::field() ?>
                            <button type="submit" style="background: #f3f5f6; border: 1px solid #0073aa; color: #0073aa; padding: 5px 10px; cursor: pointer; border-radius: 3px;">Activate</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($themes)): ?>
            <p>No themes found. Please ensure themes exist in the <code>themes/</code> directory.</p>
        <?php endif; ?>
    </div>
</div>
