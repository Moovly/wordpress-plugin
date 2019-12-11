<?php

namespace Moovly\Shortcodes\Handlers;

class TemplateShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
        ]);
    }
}
