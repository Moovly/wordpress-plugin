<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\RendersShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class RendersShortCodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(RendersShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        return $this->makeReactTag([
            'project-id' => $this->getAttribute('project-id', null),
            'view-type' => $this->getAttribute('view-type', 'grid'),
            'allow-delete' => $this->getAttribute('allow-delete', false),
        ]);
    }
}