<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\ProjectsShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class ProjectsShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(ProjectsShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        return $this->makeReactTag([
            'detail-endpoint' => $this->getAttribute('detail-endpoint', null),
        ]);
    }
}
