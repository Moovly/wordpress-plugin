<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Template;

class TemplatesShortCodeFactory
{
    public static $tag = "moovly-templates";

    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}
