<?php

namespace Moovly\Shortcodes\Handlers;

abstract class ShortcodeHandler
{
    abstract public function handle($attributes, $content = null);
}
