<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">Revisions for: <?= \App\Support\View::escape($post->title) ?></h1>
    <a href="/admin/<?= $post->type === 'page' ? 'pages' : 'posts' ?>/<?= $post->id ?>/edit" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Back to Editor</a>

    <table class="box" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #ccd0d4;">
                <th style="padding: 10px;">Date Created</th>
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($revisions)): ?>
                <tr><td colspan="3" style="padding: 10px;">No revisions found.</td></tr>
            <?php else: ?>
                <?php foreach ($revisions as $rev): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><?= \App\Support\View::escape($rev->created_at) ?></td>
                    <td style="padding: 10px;">
                        <strong><a href="/admin/revisions/<?= $rev->id ?>" style="color:#0073aa; text-decoration:none;"><?= \App\Support\View::escape($rev->title) ?></a></strong>
                    </td>
                    <td style="padding: 10px;">
                        <a href="/admin/revisions/<?= $rev->id ?>" style="color: #0073aa;">View</a> |
                        <form method="POST" action="/admin/revisions/<?= $rev->id ?>/restore" style="display:inline;" onsubmit="return confirm('Restore this revision?');">
                            <?= \App\Support\Csrf::field() ?>
                            <button type="submit" style="background:none;border:none;color:#a00;cursor:pointer;padding:0;">Restore</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
