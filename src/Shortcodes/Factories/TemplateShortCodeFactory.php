<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Template;

class TemplateShortCodeFactory
{
    public static $tag = "moovly-template";

    public static $defaultPermission = true;

    /**
     * @param Template $template
     *
     * @return string
     */
    public static function generate($template)
    {
        $tag = self::$tag;

        return sprintf("[{$tag} id='%s']", $template->getId());
    }
}
