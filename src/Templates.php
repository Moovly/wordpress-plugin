<?php

namespace Moovly;

use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Model\Variable;
use Moovly\SDK\Model\Template as MoovlyTemplate;

class Templates
{
    use MoovlyApi;

    public static $post_templates_key = 'moovly_post_templates';

    public static $post_templates_job_key = 'moovly_post_template_job';

    public function makeView()
    {
        echo '<moovly-templates></moovly-templates>';
    }

    /**
     * @return MoovlyTemplate
     */
    public function getPostTemplate()
    {
        return $this->selectPostTemplate($randomize = false);
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
    private function selectPostTemplate($randomize = false)
    {
        $templates = get_option(self::$post_templates_key);
        $template = $templates[0];

        if ($randomize) {
            $template = array_rand($templates, 1);
        }

        if (is_null($template)) {
            return (new MoovlyTemplate())->setId('')->setVariables([]);
        }

        try {
            return $this->getMoovlyService()->getTemplate($template['id']);
        } catch (\Exception $e) {
            return (new MoovlyTemplate())->setId('')->setVariables([]);
        }
    }
}
