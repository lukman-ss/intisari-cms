<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:flex; justify-content:space-between; align-items:center;">
        SEO Redirects
        <a href="/admin/redirects/create" style="background:#0073aa; color:#fff; padding:5px 15px; text-decoration:none; font-size:14px; border-radius:3px;">Add New Redirect</a>
    </h1>

    <?php if ($msg = \App\Support\Flash::get('success')): ?>
        <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border-left:4px solid #c3e6cb;">
            <?= \App\Support\View::escape($msg) ?>
        </div>
    <?php endif; ?>

    <?php if ($msg = \App\Support\Flash::get('error')): ?>
        <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-left:4px solid #f5c6cb;">
            <?= \App\Support\View::escape($msg) ?>
        </div>
    <?php endif; ?>

    <form method="GET" action="/admin/redirects" style="margin-bottom: 15px; text-align:right;">
        <input type="text" name="search" value="<?= \App\Support\View::escape($search) ?>" placeholder="Search redirects..." style="padding:5px;">
        <button type="submit" style="padding:5px 10px;">Search</button>
    </form>

    <table class="wp-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Source URL (Old)</th>
                <th>Target URL (New)</th>
                <th>Type</th>
                <th>Hits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($redirects)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px;">No redirects found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($redirects as $r): ?>
                    <tr>
                        <td><?= $r->id ?></td>
                        <td><strong><?= \App\Support\View::escape($r->source_url) ?></strong></td>
                        <td><a href="<?= \App\Support\View::escape($r->target_url) ?>" target="_blank"><?= \App\Support\View::escape($r->target_url) ?></a></td>
                        <td><?= $r->type ?></td>
                        <td><?= $r->hits ?></td>
                        <td>
                            <a href="/admin/redirects/<?= $r->id ?>/edit" style="color:#0073aa; text-decoration:none;">Edit</a> |
                            <form method="POST" action="/admin/redirects/<?= $r->id ?>/delete" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="background:none; border:none; color:#a00; cursor:pointer; padding:0; font-size:inherit;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination snippet could be added here if needed -->
</div>
