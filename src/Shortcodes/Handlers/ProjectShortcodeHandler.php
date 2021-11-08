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

        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
        ]);
    }
}