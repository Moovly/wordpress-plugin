<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class PostVideoShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;
    public function handle()
    {
        $this->checkShortcodePermission(PostVideoShortCodeFactory::$tag, true);
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