<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Dashboard</h1>

    <p style="color:#555; margin-bottom: 20px;">
        Welcome back<?= $authUser ? ', <strong>' . \App\Support\View::escape($authUser['username'] ?? 'Admin') . '</strong>' : '' ?>!
        Here's what's happening with your site.
    </p>

    <!-- Stats Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:30px;">

        <div class="box" style="text-align:center; padding:20px;">
            <div style="font-size:36px; font-weight:bold; color:#0073aa;"><?= (int)$postCount ?></div>
            <div style="color:#666; margin-top:6px;">Posts</div>
            <a href="/admin/posts" style="font-size:12px; color:#0073aa; text-decoration:none;">View all →</a>
        </div>

        <div class="box" style="text-align:center; padding:20px;">
            <div style="font-size:36px; font-weight:bold; color:#46b450;"><?= (int)$pageCount ?></div>
            <div style="color:#666; margin-top:6px;">Pages</div>
            <a href="/admin/pages" style="font-size:12px; color:#0073aa; text-decoration:none;">View all →</a>
        </div>

        <div class="box" style="text-align:center; padding:20px;">
            <div style="font-size:36px; font-weight:bold; color:#ffb900;"><?= (int)$commentCount ?></div>
            <div style="color:#666; margin-top:6px;">Pending Comments</div>
            <a href="/admin/comments" style="font-size:12px; color:#0073aa; text-decoration:none;">Moderate →</a>
        </div>

        <div class="box" style="text-align:center; padding:20px;">
            <div style="font-size:36px; font-weight:bold; color:#9b59b6;"><?= (int)$userCount ?></div>
            <div style="color:#666; margin-top:6px;">Users</div>
            <a href="/admin/users" style="font-size:12px; color:#0073aa; text-decoration:none;">View all →</a>
        </div>

        <div class="box" style="text-align:center; padding:20px;">
            <div style="font-size:36px; font-weight:bold; color:#e74c3c;"><?= (int)$mediaCount ?></div>
            <div style="color:#666; margin-top:6px;">Media Files</div>
            <a href="/admin/media" style="font-size:12px; color:#0073aa; text-decoration:none;">View all →</a>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="box" style="margin-bottom: 24px; padding: 15px;">
        <h3 style="margin-top:0;">Quick Actions</h3>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="/admin/posts/create" style="background:#0073aa; color:#fff; padding:8px 16px; text-decoration:none; border-radius:3px; font-size:13px;">+ New Post</a>
            <a href="/admin/pages/create" style="background:#46b450; color:#fff; padding:8px 16px; text-decoration:none; border-radius:3px; font-size:13px;">+ New Page</a>
            <a href="/admin/media/upload" style="background:#888; color:#fff; padding:8px 16px; text-decoration:none; border-radius:3px; font-size:13px;">Upload Media</a>
            <a href="/admin/users/create" style="background:#9b59b6; color:#fff; padding:8px 16px; text-decoration:none; border-radius:3px; font-size:13px;">+ New User</a>
            <a href="/admin/settings/general" style="background:#555; color:#fff; padding:8px 16px; text-decoration:none; border-radius:3px; font-size:13px;">Settings</a>
        </div>
    </div>

    <!-- Two Column Layout: Recent Posts + Recent Comments -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

        <!-- Recent Posts -->
        <div class="box" style="padding: 15px;">
            <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">
                Recent Posts
                <a href="/admin/posts" style="float:right; font-size:12px; font-weight:normal; color:#0073aa;">View all</a>
            </h3>
            <?php if (empty($recentPosts)): ?>
                <p style="color:#888; font-size:13px;">No posts yet. <a href="/admin/posts/create">Write your first post!</a></p>
            <?php else: ?>
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <tbody>
                        <?php foreach ($recentPosts as $rp): ?>
                        <tr style="border-bottom:1px solid #f0f0f0;">
                            <td style="padding:8px 0;">
                                <a href="/admin/posts/<?= (int)$rp['id'] ?>/edit" style="color:#0073aa; text-decoration:none; font-weight:500;">
                                    <?= \App\Support\View::escape($rp['title']) ?>
                                </a>
                                <div style="color:#888; font-size:11px; margin-top:2px;">
                                    by <?= \App\Support\View::escape($rp['author'] ?? 'Unknown') ?>
                                    &bull; <?= \App\Support\View::escape(substr($rp['created_at'] ?? '', 0, 10)) ?>
                                </div>
                            </td>
                            <td style="padding:8px 0; text-align:right;">
                                <?php
                                $statusColors = ['published' => '#46b450', 'draft' => '#888', 'trash' => '#a00'];
                                $sColor = $statusColors[$rp['status']] ?? '#888';
                                ?>
                                <span style="font-size:11px; color:<?= $sColor ?>;"><?= \App\Support\View::escape($rp['status']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Recent Comments -->
        <div class="box" style="padding: 15px;">
            <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">
                Recent Comments
                <a href="/admin/comments" style="float:right; font-size:12px; font-weight:normal; color:#0073aa;">View all</a>
            </h3>
            <?php if (empty($recentComments)): ?>
                <p style="color:#888; font-size:13px;">No comments yet.</p>
            <?php else: ?>
                <?php foreach ($recentComments as $rc): ?>
                <div style="border-bottom:1px solid #f0f0f0; padding:8px 0; font-size:13px;">
                    <div style="font-weight:500;"><?= \App\Support\View::escape($rc['author_name'] ?? 'Anonymous') ?></div>
                    <div style="color:#555; margin:2px 0;">on <em><?= \App\Support\View::escape($rc['post_title'] ?? '?') ?></em></div>
                    <div style="color:#888; font-size:12px;"><?= \App\Support\View::escape(mb_substr(strip_tags($rc['content'] ?? ''), 0, 80)) ?>...</div>
                    <?php if ($rc['status'] === 'pending'): ?>
                        <div style="margin-top:4px;">
                            <form method="POST" action="/admin/comments/<?= (int)$rc['id'] ?>/approve" style="display:inline;">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="font-size:11px; background:#46b450; color:#fff; border:none; padding:2px 8px; cursor:pointer; border-radius:2px;">Approve</button>
                            </form>
                            <form method="POST" action="/admin/comments/<?= (int)$rc['id'] ?>/spam" style="display:inline; margin-left:4px;">
                                <?= \App\Support\Csrf::field() ?>
                                <button type="submit" style="font-size:11px; background:#888; color:#fff; border:none; padding:2px 8px; cursor:pointer; border-radius:2px;">Spam</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <span style="font-size:11px; color:#46b450;"><?= \App\Support\View::escape($rc['status']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
