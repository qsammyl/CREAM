<?php

namespace App\Support;

use Mews\Purifier\Facades\Purifier;

/**
 * Allow-list HTML sanitisation for article bodies (and any other rich text).
 * Everything outside the allow-list is stripped; links get rel="noopener".
 */
class HtmlSanitizer
{
    /** @var array<string, mixed> */
    protected static array $config = [
        'HTML.Allowed' => 'p,br,strong,b,em,i,u,a[href|title|rel|target],ul,ol,li,h2,h3,h4,blockquote,hr,figure,figcaption,img[src|alt],pre,code',
        'HTML.TargetBlank' => true,
        'HTML.Nofollow' => true,
        'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true],
        'AutoFormat.RemoveEmpty' => true,
    ];

    public static function article(?string $html): ?string
    {
        if (blank($html)) {
            return null;
        }

        $clean = Purifier::clean($html, self::$config);

        return blank(trim(strip_tags($clean))) ? null : $clean;
    }
}
