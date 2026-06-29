<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Comments</h1>

    <div style="margin-top: 10px; margin-bottom: 10px;">
        <ul style="list-style:none; padding:0; display:flex; gap:15px; float:left; margin:0;">
            <li><a href="/admin/comments" style="text-decoration:none; <?= $status==='' ? 'font-weight:bold;color:#000;' : 'color:#0073aa;' ?>">All</a></li>
            <li><a href="?status=pending" style="text-decoration:none; <?= $status==='pending' ? 'font-weight:bold;color:#000;' : 'color:#0073aa;' ?>">Pending</a></li>
            <li><a href="?status=approved" style="text-decoration:none; <?= $status==='approved' ? 'font-weight:bold;color:#000;' : 'color:#0073aa;' ?>">Approved</a></li>
            <li><a href="?status=spam" style="text-decoration:none; <?= $status==='spam' ? 'font-weight:bold;color:#000;' : 'color:#0073aa;' ?>">Spam</a></li>
            <li><a href="?status=trash" style="text-decoration:none; <?= $status==='trash' ? 'font-weight:bold;color:#000;' : 'color:#0073aa;' ?>">Trash</a></li>
        </ul>
        <div style="clear:both;"></div>
    </div>

    <table class="box" style="width: 100%; border-collapse: collapse; clear: both; margin-top:15px;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px;">Author</th>
                <th style="padding: 10px;">Comment</th>
                <th style="padding: 10px;">In Response To</th>
                <th style="padding: 10px;">Submitted On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($comments)): ?>
                <tr><td colspan="4" style="padding: 10px;">No comments found.</td></tr>
            <?php else: ?>
                <?php foreach ($comments as $item): ?>
                <tr style="border-bottom: 1px solid #eee; <?= $item['status'] === 'pending' ? 'background:#fef7f1;' : '' ?>">
                    <td style="padding: 10px; vertical-align:top;">
                        <strong><?= \App\Support\View::escape($item['author_name'] ?? 'Unknown') ?></strong><br>
                        <a href="mailto:<?= \App\Support\View::escape($item['author_email'] ?? '') ?>" style="color:#0073aa; text-decoration:none;"><small><?= \App\Support\View::escape($item['author_email'] ?? '') ?></small></a>
                    </td>
                    <td style="padding: 10px; vertical-align:top;">
                        <p style="margin:0 0 10px 0;"><?= nl2br(\App\Support\View::escape($item['content'])) ?></p>
                        
                        <div style="font-size:13px;">
                            <?php if ($item['status'] !== 'approved'): ?>
                                <form method="POST" action="/admin/comments/<?= $item['id'] ?>/approve" style="display:inline;"><?= \App\Support\Csrf::field() ?><button type="submit" style="background:none;border:none;color:#0073aa;cursor:pointer;padding:0;">Approve</button></form> |
                            <?php endif; ?>
                            <?php if ($item['status'] !== 'spam'): ?>
                                <form method="POST" action="/admin/comments/<?= $item['id'] ?>/spam" style="display:inline;"><?= \App\Support\Csrf::field() ?><button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Spam</button></form> |
                            <?php endif; ?>
                            <?php if ($item['status'] !== 'trash'): ?>
                                <form method="POST" action="/admin/comments/<?= $item['id'] ?>/trash" style="display:inline;"><?= \App\Support\Csrf::field() ?><button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Trash</button></form>
                            <?php endif; ?>
                            <?php if ($item['status'] === 'trash' || $item['status'] === 'spam'): ?>
                                | <form method="POST" action="/admin/comments/<?= $item['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Delete permanently?');"><?= \App\Support\Csrf::field() ?><button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Delete Permanently</button></form>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="padding: 10px; vertical-align:top;">
                        <a href="#" style="color:#0073aa; text-decoration:none;"><?= \App\Support\View::escape($item['post_title'] ?? 'Unknown Post') ?></a>
                    </td>
                    <td style="padding: 10px; vertical-align:top; color:#666;">
                        <?= \App\Support\View::escape($item['created_at']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" style="margin-top: 15px; text-align: right;">
        <?php if ($paginator['page'] > 1): ?>
            <a href="?page=<?= $paginator['page'] - 1 ?>&status=<?= urlencode($status) ?>">&laquo; Previous</a>
        <?php endif; ?>
        <span>Page <?= $paginator['page'] ?> of <?= max($paginator['last_page'], 1) ?></span>
        <?php if ($paginator['page'] < $paginator['last_page']): ?>
            <a href="?page=<?= $paginator['page'] + 1 ?>&status=<?= urlencode($status) ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
