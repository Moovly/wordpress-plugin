<?php

namespace Moovly\Shortcodes\Handlers;

class TemplatesShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeReactTag([
            'detail-endpoint' => $this->getAttribute('detail-endpoint', null),
            'type' => $this->getAttribute('type', null),
        ]);
    }
}