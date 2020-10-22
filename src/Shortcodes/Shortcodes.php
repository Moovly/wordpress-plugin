<?php

namespace Moovly\Shortcodes;

use Moovly\Shortcodes\Factories\ProjectsShortCodeFactory;
use Moovly\Shortcodes\Factories\TemplatesShortCodeFactory;
use Moovly\Shortcodes\Handlers\ProjectShortcodeHandler;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Handlers\ProjectsShortcodeHandler;
use Moovly\Shortcodes\Handlers\TemplateShortcodeHandler;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;
use Moovly\Shortcodes\Handlers\PostVideoShortcodeHandler;
use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;
use Moovly\Shortcodes\Handlers\TemplatesShortcodeHandler;

class Shortcodes
{
    protected $shortcodes = [
        TemplateShortCodeFactory::class => TemplateShortcodeHandler::class,
        TemplatesShortCodeFactory::class => TemplatesShortcodeHandler::class,
        ProjectShortCodeFactory::class => ProjectShortcodeHandler::class,
        ProjectsShortCodeFactory::class => ProjectsShortcodeHandler::class,
        PostVideoShortCodeFactory::class => PostVideoShortcodeHandler::class,
    ];

    public function register()
    {
        foreach ($this->shortcodes as $shortcode => $handler) {
            add_shortcode($shortcode::$tag, function ($atts, $content = null) use ($shortcode, $handler) {
                return (new $handler($shortcode::$tag, $atts, $content))->handle();
            });
        }
    }
}
