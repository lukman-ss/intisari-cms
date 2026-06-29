<?php declare(strict_types=1); ?>
<div class="comments-area" style="margin-top: 40px;">
    <?php if (empty($comments)): ?>
        <p>No comments yet. Be the first to share your thoughts!</p>
    <?php else: ?>
        <h3 style="margin-bottom: 20px;"><?= count($comments) ?> Comment(s)</h3>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($comments as $comment): ?>
            <li style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                <div class="comment-meta" style="margin-bottom: 10px; font-size: 14px; color: #666;">
                    <strong><?= \App\Support\View::escape($comment['author_name'] ?? 'Unknown') ?></strong> said on <?= \App\Support\View::escape(date('F j, Y', strtotime($comment['created_at']))) ?>:
                </div>
                <div class="comment-content">
                    <?= nl2br(\App\Support\View::escape($comment['content'])) ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
