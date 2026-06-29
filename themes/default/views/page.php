<?php declare(strict_types=1); ?>
<article class="page">
    <header class="entry-header">
        <h1 class="entry-title"><?= \App\Support\View::escape($page['title']) ?></h1>
    </header>
    <div class="entry-content">
        <?php 
            echo nl2br(\App\Support\View::escape($page['content'])); 
        ?>
    </div>
</article>
