<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Project;

class ProjectsShortCodeFactory
{
    public static $tag = "moovly-projects";

    /**
     * @return string
     */
    public static function generate()
    {
        $tag = self::$tag;

        return sprintf("[{$tag}]");
    }
}
