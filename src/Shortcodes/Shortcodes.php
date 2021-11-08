<?php

namespace Moovly\Shortcodes;

use Moovly\Shortcodes\Factories\RendersShortCodeFactory;
use Moovly\Shortcodes\Factories\TemplatesShortCodeFactory;
use Moovly\Shortcodes\Handlers\ProjectShortcodeHandler;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Handlers\RendersShortCodeHandler;
use Moovly\Shortcodes\Handlers\TemplateShortcodeHandler;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;
use Moovly\Shortcodes\Handlers\PostVideoShortcodeHandler;
use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;
use Moovly\Shortcodes\Factories\ProjectsShortCodeFactory;
use Moovly\Shortcodes\Factories\RemainingCreditsShortCodeFactory;
use Moovly\Shortcodes\Handlers\ProjectsShortcodeHandler;
use Moovly\Shortcodes\Handlers\RemainingCreditsShortCodeHandler;
use Moovly\Shortcodes\Handlers\TemplatesShortcodeHandler;
use Moovly\Shortcodes\Traits\PermissionTrait;

class Shortcodes
{
    use PermissionTrait;

    protected $shortcodes = [
        TemplateShortCodeFactory::class => TemplateShortcodeHandler::class,
        TemplatesShortCodeFactory::class => TemplatesShortcodeHandler::class,
        ProjectShortCodeFactory::class => ProjectShortcodeHandler::class,
        RendersShortCodeFactory::class => RendersShortCodeHandler::class,
        PostVideoShortCodeFactory::class => PostVideoShortcodeHandler::class,
        RemainingCreditsShortCodeFactory::class => RemainingCreditsShortCodeHandler::class,
        ProjectsShortCodeFactory::class => ProjectsShortcodeHandler::class
    ];

    public function register()
    {


        foreach ($this->shortcodes as $shortcode => $handler) {
            add_shortcode($shortcode::$tag, function ($atts, $content = null) use ($shortcode, $handler) {
                return (new $handler($shortcode::$tag, $atts, $content))->handle();
            });
            $this->setDefaultPermissionWhenNotExist($shortcode::$tag, $shortcode::$defaultPermission, $this->permissionShortcodeGroup);
        }
    }
}