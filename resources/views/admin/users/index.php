<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">Users</h1>
    <a href="/admin/users/create" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Add New</a>

    <form method="GET" style="float: right; margin-bottom: 10px;">
        <input type="search" name="search" value="<?= \App\Support\View::escape($search) ?>" placeholder="Search users...">
        <button type="submit">Search Users</button>
    </form>

    <table class="box" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px;">Username</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="3" style="padding: 10px;">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        <strong><?= \App\Support\View::escape($u['username']) ?></strong>
                    </td>
                    <td style="padding: 10px;">
                        <a href="mailto:<?= \App\Support\View::escape($u['email']) ?>"><?= \App\Support\View::escape($u['email']) ?></a>
                    </td>
                    <td style="padding: 10px;">
                        <a href="/admin/users/<?= $u['id'] ?>/edit" style="color: #0073aa;">Edit</a> |
                        <form method="POST" action="/admin/users/<?= $u['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
        <span>Page <?= $paginator['page'] ?> of <?= $paginator['last_page'] ?: 1 ?></span>
        <?php if ($paginator['page'] < $paginator['last_page']): ?>
            <a href="?page=<?= $paginator['page'] + 1 ?>&search=<?= urlencode($search) ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>
