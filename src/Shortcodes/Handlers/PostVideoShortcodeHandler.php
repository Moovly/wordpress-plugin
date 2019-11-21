<?php

namespace Moovly\Shortcodes\Handlers;

class PostVideoShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return $this->makeVueTag([
            'post-id' => $this->getAttribute('post-id', $this->getPostId()),
            'autoplay' => $this->getAttribute('autoplay', 'false'),
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
