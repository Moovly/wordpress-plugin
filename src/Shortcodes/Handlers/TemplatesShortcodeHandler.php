<?php

namespace Moovly\Shortcodes\Handlers;

class TemplatesShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeReactTag([
            'tag' => $this->getAttribute('tag', null),
        ]);
    }
}
