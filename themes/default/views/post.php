<?php declare(strict_types=1); ?>
<article class="post">
    <header class="entry-header">
        <h1 class="entry-title"><?= \App\Support\View::escape($post['title']) ?></h1>
        <div class="entry-meta">
            Published on <?= date('F j, Y', strtotime($post['published_at'])) ?>
        </div>
    </header>
    <div class="entry-content">
        <?php 
            echo nl2br(\App\Support\View::escape($post['content'])); 
        ?>
    </div>
</article>

<?php if (($post['comment_status'] ?? 'open') === 'open'): ?>
    <hr style="margin-top:40px; border:0; border-top:1px solid #eee;">
    <h3>Comments</h3>
    <p>Comments are enabled but the template does not render them yet.</p>
<?php endif; ?>
