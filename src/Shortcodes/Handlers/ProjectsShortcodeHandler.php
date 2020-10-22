<?php

namespace Moovly\Shortcodes\Handlers;

class ProjectsShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeVueTag([
            'page' => $this->getAttribute('page', 0),
        ]);
    }
}
