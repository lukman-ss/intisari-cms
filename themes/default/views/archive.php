<?php declare(strict_types=1); ?>
<header class="page-header" style="margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
    <h1 class="page-title" style="margin:0;"><?= \App\Support\View::escape($archive_title ?? 'Archive') ?></h1>
</header>

<div class="post-list">
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="post">
                <header class="entry-header">
                    <h2 class="entry-title"><a href="/posts/<?= \App\Support\View::escape($post['slug']) ?>"><?= \App\Support\View::escape($post['title']) ?></a></h2>
                    <div class="entry-meta">
                        Published on <?= date('F j, Y', strtotime($post['published_at'])) ?>
                    </div>
                </header>
                <div class="entry-content">
                    <?= \App\Support\View::escape($post['excerpt'] ?: substr($post['content'], 0, 150) . '...') ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
