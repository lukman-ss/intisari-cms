<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">Pages</h1>
    <a href="/admin/pages/create" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Add New</a>

    <div style="margin-top: 10px; margin-bottom: 10px;">
        <a href="/admin/pages" style="<?= $status === '' ? 'font-weight:bold;' : '' ?>">All</a> | 
        <a href="/admin/pages?status=published" style="<?= $status === 'published' ? 'font-weight:bold;' : '' ?>">Published</a> | 
        <a href="/admin/pages?status=draft" style="<?= $status === 'draft' ? 'font-weight:bold;' : '' ?>">Draft</a> | 
        <a href="/admin/pages?status=trash" style="<?= $status === 'trash' ? 'font-weight:bold;' : '' ?>">Trash</a>
        
        <form method="GET" style="float: right;">
            <?php if ($status): ?>
                <input type="hidden" name="status" value="<?= \App\Support\View::escape($status) ?>">
            <?php endif; ?>
            <input type="search" name="search" value="<?= \App\Support\View::escape($search) ?>" placeholder="Search pages...">
            <button type="submit">Search Pages</button>
        </form>
    </div>

    <table class="box" style="width: 100%; border-collapse: collapse; clear: both;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Date</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pages)): ?>
                <tr><td colspan="4" style="padding: 10px;">No pages found.</td></tr>
            <?php else: ?>
                <?php foreach ($pages as $p): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        <strong><a href="/admin/pages/<?= $p->id ?>/edit" style="color:#0073aa; text-decoration:none;"><?= \App\Support\View::escape($p->title) ?></a></strong>
                    </td>
                    <td style="padding: 10px;"><?= \App\Support\View::escape(ucfirst($p->status)) ?></td>
                    <td style="padding: 10px;"><?= \App\Support\View::escape($p->created_at) ?></td>
                    <td style="padding: 10px;">
                        <?php if ($p->status !== 'trash'): ?>
                            <a href="/admin/pages/<?= $p->id ?>/edit" style="color: #0073aa;">Edit</a> |
                            <form method="POST" action="/admin/pages/<?= $p->id ?>/trash" style="display:inline;" onsubmit="return confirm('Move to trash?');">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Trash</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="/admin/pages/<?= $p->id ?>/restore" style="display:inline;">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="background:none;border:none;color:#0073aa;cursor:pointer;padding:0;">Restore</button>
                            </form> |
                            <form method="POST" action="/admin/pages/<?= $p->id ?>/delete" style="display:inline;" onsubmit="return confirm('Delete permanently?');">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Delete Permanently</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" style="margin-top: 15px; text-align: right;">
        <?php if ($paginator['page'] > 1): ?>
            <a href="?page=<?= $paginator['page'] - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">&laquo; Previous</a>
        <?php endif; ?>
        <span>Page <?= $paginator['page'] ?> of <?= $paginator['last_page'] ?: 1 ?></span>
        <?php if ($paginator['page'] < $paginator['last_page']): ?>
            <a href="?page=<?= $paginator['page'] + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
