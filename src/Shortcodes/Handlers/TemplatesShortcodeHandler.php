<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\TemplatesShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class TemplatesShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(TemplatesShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        return $this->makeReactTag([
            'detail-endpoint' => $this->getAttribute('detail-endpoint', null),
            'type' => $this->getAttribute('type', null),
        ]);
    }
}