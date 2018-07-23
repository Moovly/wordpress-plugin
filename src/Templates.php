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

    /**
     * @return MoovlyTemplate
     */
    public static function getPostTemplate()
    {
        return self::selectPostTemplate($randomize = false);
    }

    /**
     * @return MoovlyTemplate
     */
    public static function getRandomPostTemplate()
    {
        return self::selectPostTemplate($randomize = true);
    }

    /**
     * @param bool $randomize
     *
     * @return MoovlyTemplate
     */
    private static function selectPostTemplate($randomize = false)
    {
        $templates = get_option(self::$post_templates_key);
        $template = $templates[0];

        if ($randomize) {
            $template = array_rand($templates, 1);
        }

        if (is_null($template)) {
            return (new MoovlyTemplate)->setId('')->setVariables([]);
        }

        $variables = $template['variables']->map(
            function ($variableData) {
                return (new Variable())
                    ->setId($variableData['id'])
                    ->setName($variableData['name'])
                    ->setRequirements($variableData['requirements'])
                ;
            }
        )->toArray();

        return (new MoovlyTemplate())
            ->setId(key_exists('id', $template) ? $template['id'] : '')
            ->setName($template['name'])
            ->setVariables($variables)
        ;
    }
}
