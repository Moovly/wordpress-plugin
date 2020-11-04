<?php

namespace Moovly\Shortcodes\Factories;

class RemainingCreditsShortCodeFactory
{
    public static $tag = "moovly-remaining-credits";

    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}