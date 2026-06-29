<?php declare(strict_types=1); ?>
<h2>Latest Posts</h2>
<?php if (empty($posts)): ?>
    <p>No posts found.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <article style="margin-bottom: 30px;">
            <h3><a href="/posts/<?= \App\Support\View::escape($post->slug) ?>" style="text-decoration:none; color:#0073aa;"><?= \App\Support\View::escape($post->title) ?></a></h3>
            <div style="color: #666; font-size: 0.9em; margin-bottom: 10px;">
                Published on <?= \App\Support\View::escape($post->published_at ?? $post->created_at) ?>
            </div>
            <div>
                <?= nl2br(\App\Support\View::escape($post->excerpt ?: substr($post->content ?? '', 0, 150) . '...')) ?>
            </div>
            <a href="/posts/<?= \App\Support\View::escape($post->slug) ?>">Read More &raquo;</a>
        </article>
    <?php endforeach; ?>
    
    <div class="pagination" style="margin-top: 15px;">
        <?php if ($paginator['page'] > 1): ?>
            <a href="?page=<?= $paginator['page'] - 1 ?>">&laquo; Previous</a>
        <?php endif; ?>
        <span style="margin: 0 10px;">Page <?= $paginator['page'] ?> of <?= max($paginator['last_page'], 1) ?></span>
        <?php if ($paginator['page'] < $paginator['last_page']): ?>
            <a href="?page=<?= $paginator['page'] + 1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
