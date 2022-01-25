<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Api\Routes\Job;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

class TemplateShortcodeHandler extends ShortcodeHandler
{
    use PermissionTrait;

    public function handle()
    {
        $error = $this->checkShortcodePermission(TemplateShortCodeFactory::$tag, true);
        if ($error) {
            return $error;
        }
        $defaultSaveProject = Job::savesProjects() ? '1' : '0';

        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
            'publish-to-youtube' => $this->getAttribute('publish-to-youtube', '0'),
            'create-project' => $this->getAttribute('create-project', $defaultSaveProject),
            'create-render' => $this->getAttribute('create-render', '1'),
        ]);
    }
}
