<?php

namespace Moovly\Shortcodes\Handlers;

class PostVideoShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->make([
            'post-id' => $this->getAttribute('post-id', $this->getPostId()),
            'autoplay' => $this->getAttribute('autoplay', 'false'),
            'width' => $this->getAttribute('width', '100%'),
            'style' => $this->getAttribute('style', ''),
            'class' => $this->getAttribute('class', '')
        ]);
    }

    private function getPostId()
    {
        if (get_post_type() == 'post') {
            return get_the_id();
        }

        return null;
    }
}
