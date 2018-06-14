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

}
