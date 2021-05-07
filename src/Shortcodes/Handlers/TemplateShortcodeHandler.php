<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Api\Routes\Job;

class TemplateShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        $defaultSaveProject = Job::savesProjects()  ? '1' : '0';

        return $this->makeReactTag([
            'id' => $this->getAttribute('id'),
            'publish-to-youtube' => $this->getAttribute('publish-to-youtube', '0'),
            'create-project' => $this->getAttribute('create-project', $defaultSaveProject),
            'create-render' => $this->getAttribute('create-render', '1'),
        ]);
    }
}