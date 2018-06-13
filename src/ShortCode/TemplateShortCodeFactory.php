<?php

namespace Moovly\Shortcode;

class TemplateShortCodeFactory
{
    public static function generate($template)
    {
        return sprintf('[moovly-template id="%s"]', $template->getId());
    }
}
