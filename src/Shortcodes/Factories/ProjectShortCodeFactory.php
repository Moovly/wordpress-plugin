<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Project;

class ProjectShortCodeFactory
{
    public static $tag = "moovly-project";

    /**
     * @param Project $project
     *
     * @return string
     */
    public static function generate($project)
    {
        $tag = self::$tag;
        return sprintf("[{$tag} id='%s']", $project->getId());
    }
}
