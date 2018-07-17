<?php

namespace Moovly\Shortcodes\Handlers;

class ProjectShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->make([
            'id' => $this->getAttribute('id'),
            'autoplay' => $this->getAttribute('autoplay', 'false'),
            'width' => $this->getAttribute('width', '100%'),
            'style' => $this->getAttribute('style', ''),
            'class' => $this->getAttribute('class', '')
        ]);
    }
}
