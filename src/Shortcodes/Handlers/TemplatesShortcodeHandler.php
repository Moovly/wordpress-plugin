<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Factories\TemplatesShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class TemplatesShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $this->checkShortcodePermission(TemplatesShortCodeFactory::$tag, true);

        return $this->makeReactTag([
            'detail-endpoint' => $this->getAttribute('detail-endpoint', null),
            'type' => $this->getAttribute('type', null),
        ]);
    }
}
