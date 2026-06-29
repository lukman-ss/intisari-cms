<?php declare(strict_types=1); ?>
<?php 
$user = \App\Auth\AuthManager::guard()->user(); 
?>
<div class="comment-respond" style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 4px;">
    <h3 style="margin-top:0;">Leave a Reply</h3>
    <form action="/comments" method="POST">
        <?= \App\Support\Csrf::field() ?>
        <input type="hidden" name="post_id" value="<?= (int)($post_id ?? 0) ?>">
        
        <?php if ($user): ?>
            <p>Logged in as <?= \App\Support\View::escape($user['name']) ?>. <a href="/logout">Log out?</a></p>
        <?php else: ?>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Name *</label>
                <input type="text" name="author_name" required style="width:100%; max-width:400px; padding:8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Email *</label>
                <input type="email" name="author_email" required style="width:100%; max-width:400px; padding:8px;">
            </div>
        <?php endif; ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Comment *</label>
            <textarea name="content" rows="6" required style="width:100%; padding:8px;"></textarea>
        </div>
        
        <button type="submit" style="background:#000; color:#fff; padding:10px 20px; border:none; cursor:pointer;">Post Comment</button>
    </form>
</div>
