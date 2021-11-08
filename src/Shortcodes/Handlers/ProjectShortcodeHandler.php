<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class ProjectShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $this->checkShortcodePermission(ProjectShortCodeFactory::$tag, true);

        return $this->makeVueTag([
            'id' => $this->getAttribute('id'),
            'autoplay' => $this->getAttribute('autoplay', 'false'),
        ]);
    }
}
