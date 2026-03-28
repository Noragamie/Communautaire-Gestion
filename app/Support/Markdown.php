<?php

namespace App\Support;

use Illuminate\Support\Str;

final class Markdown
{
    /**
     * GitHub-flavored Markdown → HTML (raw HTML stripped, unsafe links disabled).
     */
    public static function toHtml(?string $markdown): string
    {
        if ($markdown === null || $markdown === '') {
            return '';
        }

        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * Plain-text excerpt for cards / listings (parses Markdown then strips tags).
     */
    public static function excerpt(?string $markdown, int $limit = 280): string
    {
        if ($markdown === null || $markdown === '') {
            return '';
        }

        $plain = strip_tags(self::toHtml($markdown));

        return Str::limit(Str::squish($plain), $limit);
    }
}
