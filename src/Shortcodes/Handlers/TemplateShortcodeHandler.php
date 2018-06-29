<?php

namespace Moovly\Shortcodes\Handlers;

class TemplateShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->make([
            'id' => $this->getAttribute('id'),
            'width' => $this->getAttribute('width', '100%'),
            'style' => $this->getAttribute('style', ''),
            'class' => $this->getAttribute('class', '')
        ]);
    }
}
