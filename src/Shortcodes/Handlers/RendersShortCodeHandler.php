<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\RendersShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class UserRendersShortCodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $this->checkShortcodePermission(RendersShortCodeFactory::$tag, true);

        return $this->makeReactTag([
            'project-id' => $this->getAttribute('project-id', null),
        ]);
    }
}