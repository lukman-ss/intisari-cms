<?php

declare(strict_types=1);

use App\Plugins\HookManager;
use App\Support\View;

HookManager::addAction('admin_notices', function () {
    $lyrics = [
        "Hello, Dolly",
        "Well, hello, Dolly",
        "It's so nice to have you back where you belong",
        "You're lookin' swell, Dolly",
        "I can tell, Dolly",
        "You're still glowin', you're still crowin'",
        "You're still goin' strong"
    ];
    $lyric = $lyrics[array_rand($lyrics)];
    $escaped = View::escape($lyric);
    
    echo "<div class='notice notice-success' style='padding: 10px; background: #fff; border-left: 4px solid #46b450; margin: 15px 0;'>
        <p style='margin: 0; font-style: italic; font-size: 14px;'>$escaped</p>
    </div>";
});
