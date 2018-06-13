<?php

namespace Moovly\Shortcodes\Factories;

class TemplateShortCodeFactory
{
    public static $tag = "moovly-template";

    public static function generate($template)
    {
        $tag = self::$tag;
        return sprintf("[{$tag} id='%s']", $template->getId());
    }
}
