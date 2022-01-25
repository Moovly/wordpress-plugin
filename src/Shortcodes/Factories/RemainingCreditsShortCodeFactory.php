<?php

namespace Moovly\Shortcodes\Factories;

class RemainingCreditsShortCodeFactory
{
    public static $tag = "moovly-remaining-credits";

    public static $defaultPermission = true;


    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}
