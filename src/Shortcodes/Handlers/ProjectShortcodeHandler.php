<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class ProjectShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(ProjectShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
            'show-title-description' => $this->getAttribute('show-title-description', false),
        ]);
    }
}