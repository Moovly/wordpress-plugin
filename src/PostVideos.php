<?php

namespace Moovly;

use Moovly\Shortcodes\Traits\ShortcodeTrait;

class PostVideos
{
    use ShortcodeTrait;

    public function makeView()
    {
        echo $this->createVueTag('moovly-post-videos');
    }
}
