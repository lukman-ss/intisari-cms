<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="<?= \App\Support\View::escape($locale ?? 'en_US') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Support\View::escape($title ?? $site_title ?? 'Intisari CMS') ?></title>
    <link rel="stylesheet" href="/themes/default/assets/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="site-branding">
                <h1 class="site-title"><a href="/"><?= \App\Support\View::escape($site_title ?? 'Intisari CMS') ?></a></h1>
                <?php if (!empty($tagline)): ?>
                    <p class="site-description"><?= \App\Support\View::escape($tagline) ?></p>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($menuItems)): ?>
            <nav class="main-navigation">
                <ul>
                    <?php foreach ($menuItems as $item): ?>
                        <li><a href="<?= \App\Support\View::escape($item['url']) ?>"><?= \App\Support\View::escape($item['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <main class="site-main">
            <?= $content ?? '' ?>
        </main>
    </div>

    <footer class="site-footer">
        <div class="container">
            &copy; <?= date('Y') ?> <?= \App\Support\View::escape($site_title ?? 'Intisari CMS') ?>. All rights reserved.
        </div>
    </footer>
</body>
</html>
