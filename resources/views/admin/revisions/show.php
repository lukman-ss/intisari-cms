<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1 style="display:inline-block; margin-right: 15px;">View Revision</h1>
    <a href="/admin/<?= $post->type === 'page' ? 'pages' : 'posts' ?>/<?= $post->id ?>/revisions" style="padding:4px 8px; border:1px solid #0073aa; text-decoration:none; color:#0073aa; border-radius:3px;">Back to Revisions</a>

    <div class="box" style="margin-top: 15px; padding: 15px;">
        <h2><?= \App\Support\View::escape($revision->title) ?></h2>
        <p style="color: #666;">Saved on: <?= \App\Support\View::escape($revision->created_at) ?></p>
        <hr>
        <div style="background: #fff; padding: 15px; border: 1px solid #eee; margin-top: 15px;">
            <?= nl2br(\App\Support\View::escape($revision->content)) ?>
        </div>
        
        <form method="POST" action="/admin/revisions/<?= $revision->id ?>/restore" style="margin-top: 15px;">
            <?= \App\Support\Csrf::field() ?>
            <button type="submit" class="button button-primary">Restore This Revision</button>
        </form>
    </div>
</div>
