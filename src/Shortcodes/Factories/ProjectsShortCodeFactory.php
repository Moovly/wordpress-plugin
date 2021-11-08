<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Project;

class ProjectsShortCodeFactory
{
    public static $tag = "moovly-projects";

    public static $defaultPermission = false;


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