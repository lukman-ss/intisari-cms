<?php declare(strict_types=1); ?>
<article>
    <h2><?= \App\Support\View::escape($post->title) ?></h2>
    <div style="color: #666; font-size: 0.9em; margin-bottom: 20px;">
        Published on <?= \App\Support\View::escape($post->published_at ?? $post->created_at) ?>
    </div>
    
    <div class="content" style="margin-top: 20px;">
        <?= nl2br(\App\Support\View::escape($post->content ?? '')) ?>
    </div>
</article>
