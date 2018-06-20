<?php

namespace Moovly\Shortcodes\Factories;

class ProjectShortCodeFactory
{
    public static $tag = "moovly-project";

    public static function generate($project)
    {
        $tag = self::$tag;
        return sprintf("[{$tag} id='%s']", $project->getId());
    }
}
