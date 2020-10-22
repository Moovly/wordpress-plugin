<?php

namespace Moovly\Shortcodes\Handlers;

class TemplatesShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeVueTag([
            'tag' => $this->getAttribute('tag', null),
        ]);
    }
}
