<?php

namespace Moovly;

use Moovly\Auth;

class Settings
{
    public $auth;

    public function initialize()
    {
        $this->auth = new Auth();
    }

    public function makeView()
    {
        echo '<moovly-settings></moovly-settings>';
    }
}
