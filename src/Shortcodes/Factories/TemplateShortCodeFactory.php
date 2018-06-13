<?php

namespace Moovly\Shortcodes\Factories;

class TemplateShortCodeFactory
{
    public static function generate($template)
    {
        return sprintf('[moovly-template id="%s"]', $template->getId());
    }
}
