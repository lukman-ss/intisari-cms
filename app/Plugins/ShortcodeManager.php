<?php

declare(strict_types=1);

namespace App\Plugins;

/**
 * Manager for parsing and executing WordPress-style shortcodes.
 */
class ShortcodeManager
{
    private static array $shortcodes = [];

    /**
     * Add a new shortcode.
     */
    public static function add(string $tag, callable $callback): void
    {
        self::$shortcodes[$tag] = $callback;
    }

    /**
     * Remove a registered shortcode.
     */
    public static function remove(string $tag): void
    {
        unset(self::$shortcodes[$tag]);
    }

    /**
     * Parse content and execute shortcodes.
     */
    public static function parse(string $content): string
    {
        if (empty(self::$shortcodes)) {
            return $content;
        }

        $pattern = self::getRegex();
        return preg_replace_callback("/$pattern/s", [self::class, 'doParse'], $content) ?? $content;
    }

    private static function doParse(array $m): string
    {
        // allow [[shortcode]] escape
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attrStr = $m[3];
        $content = isset($m[5]) ? $m[5] : null;

        $attrs = self::parseAttributes($attrStr);

        if (isset(self::$shortcodes[$tag])) {
            return (string) call_user_func(self::$shortcodes[$tag], $attrs, $content, $tag);
        }

        return $m[0];
    }

    private static function parseAttributes(string $text): array
    {
        $attrs = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $attrs[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $attrs[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $attrs[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[8])) {
                    $attrs[] = stripcslashes($m[8]);
                }
            }
        }
        return $attrs;
    }

    private static function getRegex(): string
    {
        $tagnames = array_keys(self::$shortcodes);
        $tagregexp = join('|', array_map('preg_quote', $tagnames));
        
        return '\[(\[?)(' . $tagregexp . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    }
}
