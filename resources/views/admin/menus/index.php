<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Menus</h1>
    
    <div style="display:flex; gap: 20px; margin-top: 20px;">
        <div style="flex:1;">
            <div class="box" style="padding: 20px;">
                <h2>Create a new menu</h2>
                <form method="POST" action="/admin/appearance/menus">
                    <?= \App\Support\Csrf::field() ?>
                    <input type="text" name="name" placeholder="Menu Name" required style="padding: 8px; width: 100%; max-width: 300px; margin-bottom: 10px;">
                    <br>
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Create Menu</button>
                </form>
            </div>
        </div>
        
        <div style="flex:2;">
            <div class="box" style="padding: 20px;">
                <h2>Select a menu to edit</h2>
                <?php if (empty($menus)): ?>
                    <p>No menus exist. Create one first.</p>
                <?php else: ?>
                    <ul style="list-style:none; padding:0;">
                        <?php foreach ($menus as $menu): ?>
                            <li style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                <strong><a href="/admin/appearance/menus/<?= $menu['id'] ?>" style="color:#0073aa; text-decoration:none;"><?= \App\Support\View::escape($menu['name']) ?></a></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
