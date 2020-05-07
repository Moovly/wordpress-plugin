<?php

namespace Moovly;

use Moovly\Auth;
use Moovly\Shortcodes\Traits\ShortcodeTrait;

class Settings
{
    use ShortcodeTrait;

    public function makeView()
    {
        echo $this->createVueTag('moovly-settings');
    }
}
