<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">Media Library</h1>
    <a href="/admin/media/upload" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Add New</a>

    <div style="margin-top: 10px; margin-bottom: 10px;">
        <form method="GET" style="float: right;">
            <select name="mime">
                <option value="">All media items</option>
                <option value="image" <?= $mime === 'image' ? 'selected' : '' ?>>Images</option>
                <option value="application/pdf" <?= $mime === 'application/pdf' ? 'selected' : '' ?>>PDFs</option>
            </select>
            <input type="search" name="search" value="<?= \App\Support\View::escape($search) ?>" placeholder="Search media...">
            <button type="submit">Filter</button>
        </form>
        <div style="clear:both;"></div>
    </div>

        <form method="POST" action="/admin/media/bulk">
        <?= \App\Support\Csrf::field() ?>
        <div style="margin-bottom: 10px;">
            <select name="action">
                <option value="">Bulk Actions</option>
                <option value="delete">Delete Permanently</option>
            </select>
            <button type="submit" class="button" onclick="return confirm('Are you sure you want to perform this bulk action?');">Apply</button>
        </div>
    <table class="box" style="width: 100%; border-collapse: collapse; clear: both;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px; width: 30px;"><input type="checkbox" onclick="let checkboxes = document.querySelectorAll('input[name=\'ids[]\']'); for(let cb of checkboxes) { cb.checked = this.checked; }"></th><th style="padding: 10px; width: 60px;">File</th>
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">MIME</th>
                <th style="padding: 10px;">Size</th>
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($media)): ?>
                <tr><td style="padding: 10px;"><input type="checkbox" name="ids[]" value="<?= $item['id'] ?? $item['id'] ?>"></td><td colspan="6" style="padding: 10px;">No media found.</td></tr>
            <?php else: ?>
                <?php foreach ($media as $item): ?>
                <?php $meta = json_decode($item['metadata'] ?? '{}', true) ?: []; ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><input type="checkbox" name="ids[]" value="<?= $item['id'] ?? $item['id'] ?>"></td><td style="padding: 10px;">
                        <?php if (str_starts_with($item['mime_type'], 'image/')): ?>
                            <img src="/storage/uploads/<?= \App\Support\View::escape($item['filename']) ?>" width="50" height="50" style="object-fit:cover;">
                        <?php else: ?>
                            📄
                        <?php endif; ?>
                    </td>
                    <td style="padding: 10px;">
                        <strong><a href="/admin/media/<?= $item['id'] ?>/edit" style="color:#0073aa; text-decoration:none;"><?= \App\Support\View::escape($meta['title'] ?? $item['filename']) ?></a></strong><br>
                        <small style="color:#666;"><?= \App\Support\View::escape($item['filename']) ?></small>
                    </td>
                    <td style="padding: 10px;"><?= \App\Support\View::escape($item['mime_type']) ?></td>
                    <td style="padding: 10px;"><?= number_format((float)$item['size'] / 1024, 2) ?> KB</td>
                    <td style="padding: 10px;"><?= \App\Support\View::escape($item['created_at']) ?></td>
                    <td style="padding: 10px;">
                        <a href="/admin/media/<?= $item['id'] ?>/edit" style="color: #0073aa;">Edit</a> |
                        <form method="POST" action="/admin/media/<?= $item['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete permanently?');">
                            <?= \App\Support\Csrf::field() ?>
                            <button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </form>

    <div class="pagination" style="margin-top: 15px; text-align: right;">
        <?php if ($paginator['page'] > 1): ?>
            <a href="?page=<?= $paginator['page'] - 1 ?>&search=<?= urlencode($search) ?>&mime=<?= urlencode($mime) ?>">&laquo; Previous</a>
        <?php endif; ?>
        <span>Page <?= $paginator['page'] ?> of <?= max($paginator['last_page'], 1) ?></span>
        <?php if ($paginator['page'] < $paginator['last_page']): ?>
            <a href="?page=<?= $paginator['page'] + 1 ?>&search=<?= urlencode($search) ?>&mime=<?= urlencode($mime) ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
