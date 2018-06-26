<?php

namespace Moovly\Shortcodes\Factories;

use Moovly\SDK\Model\Value;

class PostVideoShortCodeFactory
{
    public static $tag = "moovly-post-video";

    public static function generate(?Value $value)
    {
        if (!$value) {
            return '';
        }

        return sprintf("[{$tag} url='%s']", $value->getUrl());
    }
}
