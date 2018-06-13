<?php

namespace Moovly\Shortcodes;

use Moovly\Shortcodes\Handlers\TemplateShortcodeHandler;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;

class Shortcodes
{
    protected $shortcodes = [
        TemplateShortCodeFactory::class => TemplateShortcodeHandler::class,
    ];

    public function register()
    {
        foreach ($this->shortcodes as $shortcode=>$handler) {
            add_shortcode($shortcode::$tag, function ($atts, $content = null) use ($handler) {
                return (new $handler)->handle($atts, $content);
            });
        }
    }
}