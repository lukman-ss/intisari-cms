<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Comment</h1>
    <div class="box" style="max-width:700px; margin-top:20px; padding:20px;">

        <div style="background:#f5f5f5; padding:12px; border-left:4px solid #ddd; margin-bottom:20px; font-size:13px;">
            <strong>Post:</strong> <?= \App\Support\View::escape($comment['post_title'] ?? 'Unknown') ?><br>
            <strong>Date:</strong> <?= \App\Support\View::escape(substr($comment['created_at'] ?? '', 0, 16)) ?><br>
            <strong>Status:</strong> <?= \App\Support\View::escape($comment['status'] ?? '') ?>
        </div>

        <form method="POST" action="/admin/comments/<?= (int)$comment['id'] ?>/update">
            <?= \App\Support\Csrf::field() ?>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Author Name</label>
                <input type="text" name="author_name"
                    value="<?= \App\Support\View::escape($comment['author_name'] ?? '') ?>"
                    style="width:100%; padding:8px; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Author Email</label>
                <input type="email" name="author_email"
                    value="<?= \App\Support\View::escape($comment['author_email'] ?? '') ?>"
                    style="width:100%; padding:8px; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px; font-weight:bold;">Comment Content <span style="color:#a00;">*</span></label>
                <textarea name="content" rows="6"
                    style="width:100%; padding:8px; box-sizing:border-box; font-family:inherit;" required><?= \App\Support\View::escape($comment['content'] ?? '') ?></textarea>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:9px 20px; cursor:pointer; border-radius:3px;">
                    Update Comment
                </button>
                <a href="/admin/comments" style="padding:9px 16px; color:#555; text-decoration:none; font-size:13px;">
                    ← Back to Comments
                </a>
            </div>
        </form>
    </div>
</div>
