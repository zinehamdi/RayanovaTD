<?php

namespace App\Services;

use ArPHP\I18N\Arabic;

class PdfArabicHelper
{
    protected static ?Arabic $arabic = null;

    public static function instance(): Arabic
    {
        if (self::$arabic === null) {
            self::$arabic = new Arabic();
        }
        return self::$arabic;
    }

    /**
     * Shape and reorder Arabic text for Dompdf with bidirectional support
     */
    public static function format(?string $text, bool $isRtl = true): string
    {
        if ($text === null || $text === '' || !$isRtl) {
            return (string) $text;
        }

        // Only process if string contains Arabic characters
        if (!preg_match('/\p{Arabic}/u', $text)) {
            return $text;
        }

        try {
            return self::instance()->utf8Glyphs($text);
        } catch (\Throwable $e) {
            return $text;
        }
    }
}
