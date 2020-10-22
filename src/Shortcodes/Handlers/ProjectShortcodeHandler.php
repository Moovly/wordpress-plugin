<?php

namespace Moovly\Shortcodes\Handlers;

class ProjectShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
            'autoplay' => $this->getAttribute('autoplay', 'false'),
        ]);
    }
}
