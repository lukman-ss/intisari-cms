<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Intisari CMS Installation</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; background: #f1f1f1; }
        .box { background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .submit-btn { background: #0073aa; color: #fff; border: none; padding: 10px 15px; cursor: pointer; font-size: 16px; }
        .error-msg { color: #dc3232; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Welcome to Intisari CMS</h1>
        <?php if ($message = \App\Support\Flash::get('error')): ?>
            <div class="error-msg"><?= \App\Support\View::escape($message) ?></div>
        <?php endif; ?>
        
        <?php if (in_array(false, $requirements, true)): ?>
            <div class="error-msg">
                <p><strong>System Requirements Missing:</strong></p>
                <ul>
                    <li>PHP >= 8.2: <?= $requirements['php_version'] ? 'OK' : 'FAIL' ?></li>
                    <li>Storage Writable: <?= $requirements['storage_writable'] ? 'OK' : 'FAIL' ?></li>
                    <li>Database Dir Writable: <?= $requirements['database_dir_writable'] ? 'OK' : 'FAIL' ?></li>
                </ul>
                <p>Please fix these issues to continue.</p>
            </div>
        <?php else: ?>
            <p>Please provide the following information to install Intisari CMS.</p>
            <form method="POST" action="/install">
                <?= \App\Support\Csrf::field() ?>
                
                <div class="input-group">
                    <label>Site Title</label>
                    <input type="text" name="site_title" required>
                </div>
                <div class="input-group">
                    <label>Admin Name</label>
                    <input type="text" name="admin_name" required>
                </div>
                <div class="input-group">
                    <label>Admin Email</label>
                    <input type="email" name="admin_email" required>
                </div>
                <div class="input-group">
                    <label>Admin Username</label>
                    <input type="text" name="admin_username" required>
                </div>
                <div class="input-group">
                    <label>Admin Password</label>
                    <input type="password" name="admin_password" minlength="8" required>
                </div>
                <div class="input-group">
                    <label>Database Driver</label>
                    <input type="text" name="db_driver" value="sqlite" readonly>
                </div>
                <div class="input-group">
                    <label>Database Path</label>
                    <input type="text" name="db_path" value="database/cms.sqlite" readonly>
                </div>
                
                <button type="submit" class="submit-btn">Install Intisari CMS</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
