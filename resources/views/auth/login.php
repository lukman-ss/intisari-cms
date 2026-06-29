<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In &lsaquo; Intisari CMS</title>
    <style>
        body { font-family: sans-serif; background: #f1f1f1; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,.04); width: 320px; }
        .login-box h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; }
        .input-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .submit-btn { background: #0073aa; color: #fff; border: none; padding: 10px 15px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Intisari CMS</h1>
        
        <?php if (\App\Support\Flash::has('error')): ?>
            <p style="color: red;"><?= \App\Support\View::escape((string)\App\Support\Flash::get('error')) ?></p>
        <?php endif; ?>

        <form method="POST" action="/login">
            <?= \App\Support\Csrf::field() ?>
            <div class="input-group">
                <label for="username">Username or Email Address</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="submit-btn">Log In</button>
        </form>
    </div>
</body>
</html>
