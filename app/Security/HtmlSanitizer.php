<?php

declare(strict_types=1);

namespace App\Security;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;

class HtmlSanitizer
{
    /**
     * Sanitizes HTML content.
     */
    public static function sanitize(string $html): string
    {
        if (CapabilityChecker::checkCurrentUser(Capability::UNFILTERED_HTML)) {
            return $html;
        }

        $allowedTags = '<a><p><br><b><i><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><img>';
        return strip_tags($html, $allowedTags);
    }
}
