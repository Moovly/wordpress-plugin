<?php

namespace Moovly;

use Moovly\SDK\Model\Variable;
use Moovly\SDK\Model\Template as MoovlyTemplate;

class Templates
{
    public static $post_templates_key = 'moovly_post_templates';

    public static $post_templates_job_key = 'moovly_post_template_job';

    public function makeView()
    {
        echo '<moovly-templates></moovly-templates>';
    }

    public static function getPostTemplate() : MoovlyTemplate
    {
        return self::selectPostTemplate($randomize = false);
    }

    public static function getRandomPostTemplate() : MoovlyTemplate
    {
        return self::selectPostTemplate($randomize = true);
    }

    private static function selectPostTemplate($randomize = false) : MoovlyTemplate
    {
        $templates = get_option(self::$post_templates_key);

        if ($randomize) {
            $template = array_rand($templates, 1);
        } else {
            $template = $templates[0];
        }

        if (is_null($template)) {
            return (new MoovlyTemplate)->setId('')->setVariables([]);
        }

        return (new MoovlyTemplate())
        ->setId($template['id'] ?? '')
        ->setName($template['name'])
        ->setVariables(
            $template['variables']->map(function ($variableData) {
                return (new Variable())
                    ->setId($variableData['id'])
                    ->setName($variableData['name'])
                    ->setRequirements($variableData['requirements']);
            })->toArray()
        );
    }
}
