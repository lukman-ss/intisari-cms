<?php

declare(strict_types=1);

namespace App\Support;

class Slug
{
    public static function generate(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text) ?? $text;
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text;
        $text = preg_replace('~[^-\w]+~', '', $text) ?? $text;
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text) ?? $text;
        
        return strtolower($text);
    }
}
