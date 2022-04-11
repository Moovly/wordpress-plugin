<?php

namespace Moovly\Shortcodes\Handlers;

use Moovly\Shortcodes\Traits\PermissionTrait;
use Moovly\Shortcodes\Traits\SettingTrait;

abstract class ShortcodeHandler
{
    use PermissionTrait, SettingTrait;

    protected $attributes;

    protected $content;

    protected $tag;

    public function __construct($tag, $attributes, $content = null)
    {
        $this->attributes = $attributes;
        $this->content = $content;
        $this->tag = $tag;
    }

    abstract public function handle();

    /**
     * @param string $name
     * @param string $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = '')
    {
        return shortcode_atts([
            $name => $default,
        ], $this->attributes)[$name];
    }

    /**
     * @param \stdClass $attributes
     *
     * @return string
     */
    public function makeVueTag($attributes)
    {
        $name = $this->tag . rand(1, 1000000);

        $rest = rest_url();

        return "<div id='{$name}' class='moovly-plugin {$this->tag}'>" .
            "<{$this->tag} rest-api-call='{$rest}' {$this->mapAttributesToHtmlProperties($attributes)} ></{$this->tag}>" .
            "</div>";
    }
    /**
     * @param \stdClass $attributes
     *
     * @return string
     */
    public function makeReactTag($attributes)
    {
        $name = $this->tag . rand(1, 1000000);
        $rest = rest_url();
        return "<div id='{$name}' data-moovly-plugin-locale='{$this->getSetting($this->localeSettingKey,$this->defaultLocale)}' data-rest='{$rest}' class='{$this->tag}' {$this->mapAttributesToHtmlProperties($attributes, true)}></div>";
    }

    /**
     * @param $attributes
     *
     * @return string
     */
    protected function mapAttributesToHtmlProperties($attributes, $asDataProperty = false)
    {
        $attributes = array_merge([
            'width' => $this->getAttribute('width', '100%'),
            'class' => $this->getAttribute('class'),
        ], $attributes);

        array_walk($attributes, function (&$value, $key) use ($asDataProperty) {
            $property = $asDataProperty ? "data-${key}" : $key;
            $value = "$property='$value'";
        });

        return implode(' ', $attributes);
    }
}