<?php

namespace Moovly\Shortcodes\Factories;


class TemplatesShortCodeFactory
{
    public static $tag = "moovly-templates";

    public static $defaultPermission = false;


    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}