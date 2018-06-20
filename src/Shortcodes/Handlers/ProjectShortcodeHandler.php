<?php

namespace Moovly\Shortcodes\Handlers;

class ProjectShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->make([
            'id' => $this->getAttribute('id'),
        ]);
    }
}
