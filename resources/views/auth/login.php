<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName ?? 'Intisari CMS') ?> — Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f4f6f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 8px; padding: 2rem; width: 100%; max-width: 380px; box-shadow: 0 2px 12px rgba(0,0,0,.1); }
        h1 { font-size: 1.4rem; color: #1a1a2e; margin-bottom: 0.25rem; }
        .subtitle { font-size: 0.85rem; color: #888; margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #444; margin-bottom: 0.25rem; }
        input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; margin-bottom: 1rem; }
        .btn { width: 100%; padding: 0.6rem; background: #0f3460; color: #fff; border: none; border-radius: 4px; font-size: 0.95rem; cursor: pointer; }
        .btn:hover { background: #1a1a2e; }
        .alert { padding: 0.65rem 0.9rem; background: #fdecea; color: #c0392b; border-radius: 4px; font-size: 0.85rem; margin-bottom: 1rem; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="card">
    <h1><?= htmlspecialchars($appName ?? 'Intisari CMS') ?></h1>
    <p class="subtitle">Sign in to continue</p>
    <?php if (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars((string) $error) ?></div>
    <?php endif; ?>
    <form action="/admin/login" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
        <button type="submit" class="btn">Sign In</button>
    </form>
</div>
</body>
</html>
