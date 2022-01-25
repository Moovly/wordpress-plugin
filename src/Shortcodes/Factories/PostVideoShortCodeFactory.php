<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Value;

class PostVideoShortCodeFactory
{
    public static $tag = "moovly-post-video";

    public static $defaultPermission = true;


    /**
     * @param WP_Post $post
     *
     * @return string
     */
    public static function generate($post)
    {
        $tag = self::$tag;

        return sprintf("[{$tag} post-id='%s']", $post->ID);
    }
}
