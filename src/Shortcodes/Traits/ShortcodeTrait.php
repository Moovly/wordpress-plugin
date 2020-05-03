<?php

namespace Moovly\Shortcodes\Traits;

trait ShortcodeTrait
{

    /**
     * @param \stdClass $attributes
     *
     * @return string
     */
    public function createVueTag($tag, $attributes = [])
    {
        $name = $tag . rand(1, 1000000);

        $rest = rest_url();

        return "<div id='{$name}' class='moovly-plugin {$tag}'>" .
            "<{$tag} rest-api-call='{$rest}' {$this->mapAttributesToHtmlProperties($attributes)} ></{$tag}>" .
            "</div>";
    }
    /**
     * @param \stdClass $attributes
     *
     * @return string
     */
    public function createReactTag($tag, $attributes)
    {
        $name = $tag . rand(1, 1000000);
        $rest = rest_url();
        return "<div id='{$name}' data-rest='{$rest}' class='{$tag}' {$this->mapAttributesToHtmlProperties($attributes, true)}></div>";
    }



    /**
     * @param $attributes
     *
     * @return string
     */
    protected function mapAttributesToHtmlProperties($attributes, $asDataProperty = false)
    {
        $attributes = array_merge([
            'width' => '100%',
        ], $attributes);

        array_walk($attributes, function (&$value, $key) use ($asDataProperty) {
            $property = $asDataProperty ? "data-${key}" : $key;
            $value = "$property='$value'";
        });

        return implode(' ', $attributes);
    }
}
