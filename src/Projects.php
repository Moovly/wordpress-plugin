<?php

namespace Moovly;

use Moovly\Shortcodes\Traits\ShortcodeTrait;

class Projects
{
    use ShortcodeTrait;

    public function makeView()
    {
        echo $this->createVueTag('moovly-projects');
    }
}
