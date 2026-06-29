<?php

$views = [
    'resources/views/admin/posts/index.php' => [],
    'resources/views/admin/pages/index.php' => [],
    'resources/views/admin/media/index.php' => [],
    'resources/views/admin/comments/index.php' => [],
    'resources/views/admin/users/index.php' => []
];

foreach ($views as $path => $data) {
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);

    $content = preg_replace('/(<tr><td[^>]*>)<input type="checkbox"[^>]*><\/td>(<td colspan="\d+"[^>]*>No [a-z]+ found)/is', '$1$2', $content);
    $content = preg_replace('/(<tr><td[^>]*>)<input type="checkbox"[^>]*>(<\/td><td colspan="\d+"[^>]*>No [a-z]+ found)/is', '$1$2', $content);

    if (str_contains($content, 'foreach ($pages as $p)')) {
        $content = str_replace('<?= $post->id ?>', '<?= $p->id ?>', $content);
    }
    if (str_contains($content, 'foreach ($users as $u)')) {
        $content = str_replace('<?= $user[\'id\'] ?? $user->id ?>', '<?= $u[\'id\'] ?? $u[\'id\'] ?>', $content);
    }
    if (str_contains($content, 'foreach ($media as $item)')) {
        $content = str_replace('<?= $media->id ?>', '<?= $item[\'id\'] ?? $item[\'id\'] ?>', $content);
    }
    if (str_contains($content, 'foreach ($comments as $c)')) {
        $content = str_replace('<?= $comment->id ?>', '<?= $c[\'id\'] ?? $c[\'id\'] ?>', $content);
    }

    file_put_contents($path, $content);
}

// Ensure comments has search and status filter forms
$commentsPath = 'resources/views/admin/comments/index.php';
$cContent = file_get_contents($commentsPath);
if (!str_contains($cContent, 'name="search"')) {
    $searchForm = <<<HTML
    <div style="margin-top: 10px; margin-bottom: 10px;">
        <a href="/admin/comments" style="<?= \$status === '' ? 'font-weight:bold;' : '' ?>">All</a> | 
        <a href="/admin/comments?status=pending" style="<?= \$status === 'pending' ? 'font-weight:bold;' : '' ?>">Pending</a> | 
        <a href="/admin/comments?status=approved" style="<?= \$status === 'approved' ? 'font-weight:bold;' : '' ?>">Approved</a> | 
        <a href="/admin/comments?status=spam" style="<?= \$status === 'spam' ? 'font-weight:bold;' : '' ?>">Spam</a> | 
        <a href="/admin/comments?status=trash" style="<?= \$status === 'trash' ? 'font-weight:bold;' : '' ?>">Trash</a>
        
        <form method="GET" style="float: right;">
            <?php if (\$status): ?>
                <input type="hidden" name="status" value="<?= \App\Support\View::escape(\$status) ?>">
            <?php endif; ?>
            <input type="search" name="search" value="<?= \App\Support\View::escape(\$search ?? '') ?>" placeholder="Search comments...">
            <button type="submit">Search Comments</button>
        </form>
    </div>
HTML;
    $cContent = str_replace('<form method="POST" action="/admin/comments/bulk">', $searchForm . "\n" . '    <form method="POST" action="/admin/comments/bulk">', $cContent);
    file_put_contents($commentsPath, $cContent);
}

// Media also needs search and type (mime) filter
$mediaPath = 'resources/views/admin/media/index.php';
$mContent = file_get_contents($mediaPath);
if (!str_contains($mContent, 'name="search"')) {
    $searchForm = <<<HTML
    <div style="margin-top: 10px; margin-bottom: 10px;">
        <a href="/admin/media" style="<?= \$mime === '' ? 'font-weight:bold;' : '' ?>">All</a> | 
        <a href="/admin/media?mime=image" style="<?= str_starts_with(\$mime, 'image') ? 'font-weight:bold;' : '' ?>">Images</a> | 
        <a href="/admin/media?mime=application" style="<?= str_starts_with(\$mime, 'application') ? 'font-weight:bold;' : '' ?>">Documents</a>
        
        <form method="GET" style="float: right;">
            <?php if (\$mime): ?>
                <input type="hidden" name="mime" value="<?= \App\Support\View::escape(\$mime) ?>">
            <?php endif; ?>
            <input type="search" name="search" value="<?= \App\Support\View::escape(\$search ?? '') ?>" placeholder="Search media...">
            <button type="submit">Search Media</button>
        </form>
    </div>
HTML;
    $mContent = str_replace('<form method="POST" action="/admin/media/bulk">', $searchForm . "\n" . '    <form method="POST" action="/admin/media/bulk">', $mContent);
    file_put_contents($mediaPath, $mContent);
}
