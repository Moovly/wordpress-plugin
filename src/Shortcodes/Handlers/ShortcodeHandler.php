<?php

namespace Moovly\Shortcodes\Handlers;

abstract class ShortcodeHandler
{
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
    public function make($attributes)
    {
        return "<div id='{$this->tag}' class='moovly-plugin' >" .
            "<{$this->tag} {$this->mapAttributesToHtmlProperties($attributes)} ></{$this->tag}>" .
            "</div>"
        ;
    }

    /**
     * @param $attributes
     *
     * @return string
     */
    protected function mapAttributesToHtmlProperties($attributes)
    {
        $attributes = array_merge([
            'width' => $this->getAttribute('width', '100%'),
            'class' => $this->getAttribute('class'),
        ], $attributes);

        array_walk($attributes, function (&$value, $key) {
            $value = "$key='$value'";
        });

        return implode(' ', $attributes);
    }
}
