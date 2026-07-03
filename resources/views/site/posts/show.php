<?php declare(strict_types=1); ?>
<article>
    <h2><?= \App\Support\View::escape($post->title) ?></h2>
    <div style="color: #666; font-size: 0.9em; margin-bottom: 20px;">
        Published on <?= \App\Support\View::escape(substr($post->published_at ?? $post->created_at ?? '', 0, 10)) ?>
    </div>
    
    <?php if (!empty($post->featured_image_url)): ?>
        <div style="margin-bottom: 20px;">
            <img src="/storage/uploads/<?= \App\Support\View::escape($post->featured_image_url) ?>" alt="" style="max-width: 100%; height: auto; border-radius: 4px;">
        </div>
    <?php endif; ?>

    <div class="content" style="margin-top: 20px;">
        <?php 
            $contentHtml = nl2br(\App\Support\View::escape($post->content ?? '')); 
            echo \App\Plugins\ShortcodeManager::parse($contentHtml);
        ?>
    </div>

    <?php
    // We can fetch terms via repository or if they are already joined.
    // For simplicity, let's assume we fetch them if needed, or if they are just passed to view.
    $termRepo = new \App\Repositories\TermRepository();
    $categories = $termRepo->getTermsForPost((int)$post->id, 'category');
    $tags = $termRepo->getTermsForPost((int)$post->id, 'post_tag');
    ?>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9em; color: #555;">
        <?php if (!empty($categories)): ?>
            <div style="margin-bottom: 5px;">
                <strong>Categories:</strong> 
                <?= implode(', ', array_map(fn($c) => \App\Support\View::escape($c['name']), $categories)) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($tags)): ?>
            <div>
                <strong>Tags:</strong> 
                <?= implode(', ', array_map(fn($t) => \App\Support\View::escape($t['name']), $tags)) ?>
            </div>
        <?php endif; ?>
    </div>
</article>
