<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">Edit Menu: <?= \App\Support\View::escape($menu['name']) ?></h1>
    <a href="/admin/appearance/menus" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Back to Menus</a>

    <div style="display:flex; gap: 20px; margin-top: 20px; align-items: flex-start;">
        <div style="width: 300px;">
            <div class="box" style="padding: 15px; margin-bottom: 20px;">
                <h3>Add Pages / Posts</h3>
                <form method="POST" action="/admin/appearance/menus/<?= $menu['id'] ?>/items">
                    <?= \App\Support\Csrf::field() ?>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                        <?php foreach ($contentList as $content): ?>
                            <label style="display:block; margin-bottom: 5px;">
                                <input type="checkbox" name="content_ids[]" value="<?= $content['id'] ?>"> 
                                <?= \App\Support\View::escape($content['title']) ?> 
                                <small style="color:#666;">(<?= $content['type'] ?>)</small>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" style="background:#f3f5f6; border:1px solid #0073aa; color:#0073aa; padding:6px 12px; cursor:pointer;">Add to Menu</button>
                </form>
            </div>

            <div class="box" style="padding: 15px;">
                <h3>Add Custom Link</h3>
                <form method="POST" action="/admin/appearance/menus/<?= $menu['id'] ?>/items">
                    <?= \App\Support\Csrf::field() ?>
                    <p>
                        <label style="display:block; font-size:13px;">URL</label>
                        <input type="text" name="custom_url" value="http://" style="width:100%; padding:6px;">
                    </p>
                    <p>
                        <label style="display:block; font-size:13px;">Link Text</label>
                        <input type="text" name="custom_title" style="width:100%; padding:6px;">
                    </p>
                    <button type="submit" style="background:#f3f5f6; border:1px solid #0073aa; color:#0073aa; padding:6px 12px; cursor:pointer;">Add to Menu</button>
                </form>
            </div>
        </div>
        
        <div style="flex:1;">
            <div class="box" style="padding: 20px;">
                <form method="POST" action="/admin/appearance/menus/<?= $menu['id'] ?>">
                    <?= \App\Support\Csrf::field() ?>
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight:bold;">Menu Name:</label>
                        <input type="text" name="name" value="<?= \App\Support\View::escape($menu['name']) ?>" required style="padding:6px; margin-left: 10px;">
                    </div>
                    
                    <h3 style="margin-top:30px;">Menu Structure</h3>
                    <p style="font-size:13px; color:#666;">Set the title, URL, order, and parent for each item.</p>
                    
                    <?php if (empty($items)): ?>
                        <p>No items in this menu.</p>
                    <?php else: ?>
                        <div style="background:#f9f9f9; padding: 15px; border:1px solid #eee;">
                            <?php foreach ($items as $item): ?>
                                <div style="border:1px solid #ddd; background:#fff; padding: 15px; margin-bottom: 10px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                                        <strong><?= \App\Support\View::escape($item['title']) ?></strong>
                                        
                                        <button type="submit" formaction="/admin/appearance/menus/<?= $menu['id'] ?>/items/<?= $item['id'] ?>/delete" style="background:none; border:none; color:#a00; text-decoration:underline; cursor:pointer; font-size:13px; padding:0;">Remove</button>
                                        
                                    </div>
                                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display:block; font-size:12px; color:#666;">Title</label>
                                            <input type="text" name="items[<?= $item['id'] ?>][title]" value="<?= \App\Support\View::escape($item['title']) ?>" style="width:100%; padding:4px;">
                                        </div>
                                        <div>
                                            <label style="display:block; font-size:12px; color:#666;">URL</label>
                                            <input type="text" name="items[<?= $item['id'] ?>][url]" value="<?= \App\Support\View::escape($item['url']) ?>" style="width:100%; padding:4px;">
                                        </div>
                                        <div>
                                            <label style="display:block; font-size:12px; color:#666;">Order</label>
                                            <input type="number" name="items[<?= $item['id'] ?>][order_index]" value="<?= $item['order_index'] ?>" style="width:100%; padding:4px;">
                                        </div>
                                        <div>
                                            <label style="display:block; font-size:12px; color:#666;">Parent Item ID (0 = root)</label>
                                            <input type="number" name="items[<?= $item['id'] ?>][parent_id]" value="<?= $item['parent_id'] ?>" style="width:100%; padding:4px;">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 20px; text-align:right;">
                        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; font-weight:bold;">Save Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
