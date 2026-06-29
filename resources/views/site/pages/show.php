<?php declare(strict_types=1); ?>
<article>
    <h2><?= \App\Support\View::escape($page->title) ?></h2>
    
    <div class="content" style="margin-top: 20px;">
        <?= nl2br(\App\Support\View::escape($page->content ?? '')) ?>
    </div>
</article>
