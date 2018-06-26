<?php

namespace Moovly\Shortcodes\Handlers;

class PostVideoShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->make([
            'post-id' => $this->getAttribute('post-id'),
        ]);
    }
}
