<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= \App\Support\View::escape($title ?? 'Intisari CMS') ?></title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; line-height: 1.6; }
        header { border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #0073aa; }
        footer { border-top: 1px solid #ccc; margin-top: 40px; padding-top: 20px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <header>
        <h1><a href="/" style="text-decoration:none; color:inherit;">Intisari CMS</a></h1>
        <nav>
            <a href="/">Home</a>
            <a href="/posts">Blog</a>
        </nav>
    </header>
    
    <main>
        <?= $content ?? '' ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Intisari CMS
    </footer>
</body>
</html>
