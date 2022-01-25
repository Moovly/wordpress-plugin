<?php

namespace Moovly\Shortcodes\Factories;

class RendersShortCodeFactory
{
    public static $tag = "moovly-renders";

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
