<?php

namespace Moovly\Shortcodes\Handlers;

class UserRendersShortCodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeReactTag([]);
    }
}