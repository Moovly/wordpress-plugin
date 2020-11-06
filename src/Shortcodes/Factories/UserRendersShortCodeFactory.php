<?php

namespace Moovly\Shortcodes\Factories;

class UserRendersShortCodeFactory
{
    public static $tag = "moovly-renders";

    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}