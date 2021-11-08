<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\ProjectsShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class ProjectsShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $this->checkShortcodePermission(ProjectsShortCodeFactory::$tag, true);

        return $this->makeReactTag([
            'detail-endpoint' => $this->getAttribute('detail-endpoint', null),
        ]);
    }
}