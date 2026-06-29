<?php declare(strict_types=1); ?>
<div class="wrap" style="display: flex; gap: 30px;">
    
    <div style="width: 300px;">
        <h2>Add New Tag</h2>
        <form method="POST" action="/admin/tags" class="box" style="padding: 15px;">
            <?= \App\Support\Csrf::field() ?>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Name</label>
                <input type="text" name="name" style="width:100%; padding:8px;" required>
                <p style="font-size:12px; color:#666; margin-top:5px;">The name is how it appears on your site.</p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Slug</label>
                <input type="text" name="slug" style="width:100%; padding:8px;">
                <p style="font-size:12px; color:#666; margin-top:5px;">The "slug" is the URL-friendly version of the name.</p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Description</label>
                <textarea name="description" rows="4" style="width:100%; padding:8px;"></textarea>
            </div>
            
            <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:8px 15px; cursor:pointer;">Add New Tag</button>
        </form>
    </div>

    <div style="flex: 1;">
        <h1 style="display:inline-block;">Tags</h1>
        <form method="GET" style="float: right; margin-top: 15px;">
            <input type="search" name="search" value="<?= \App\Support\View::escape($search) ?>">
            <button type="submit">Search</button>
        </form>

        <table class="box" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Description</th>
                    <th style="padding: 10px;">Slug</th>
                    <th style="padding: 10px;">Count</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tags)): ?>
                    <tr><td colspan="5" style="padding: 10px;">No tags found.</td></tr>
                <?php else: ?>
                    <?php foreach ($tags as $tag): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">
                            <strong><?= \App\Support\View::escape($tag['name']) ?></strong>
                        </td>
                        <td style="padding: 10px;"><?= \App\Support\View::escape($tag['description'] ?? '') ?></td>
                        <td style="padding: 10px;"><?= \App\Support\View::escape($tag['slug']) ?></td>
                        <td style="padding: 10px;"><a href="/admin/posts?tag=<?= \App\Support\View::escape($tag['slug']) ?>"><?= $tag['count'] ?? 0 ?></a></td>
                        <td style="padding: 10px;">
                            <a href="/admin/tags/<?= $tag['id'] ?>/edit" style="color: #0073aa;">Edit</a> 
                            | 
                            <form method="POST" action="/admin/tags/<?= $tag['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete permanently?');">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination" style="margin-top: 15px; text-align: right;">
            <?php if ($paginator['page'] > 1): ?>
                <a href="?page=<?= $paginator['page'] - 1 ?>&search=<?= urlencode($search) ?>">&laquo; Previous</a>
            <?php endif; ?>
            <span>Page <?= $paginator['page'] ?> of <?= max($paginator['last_page'], 1) ?></span>
            <?php if ($paginator['page'] < $paginator['last_page']): ?>
                <a href="?page=<?= $paginator['page'] + 1 ?>&search=<?= urlencode($search) ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>
