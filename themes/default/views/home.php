<?php declare(strict_types=1); ?>
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
