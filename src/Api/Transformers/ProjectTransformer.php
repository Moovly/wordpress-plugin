<?php

namespace Moovly\Api\Transformers;

use Moovly\SDK\Model\Project;
use Moovly\SDK\Model\Render;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;

class ProjectTransformer
{
    /**
     * @param Project $project
     *
     * @return array
     */
    public static function transform($project)
    {
        $renders = array_map(function ($render) {
            /** @var Render $render */
            return [
                'id' => $render->getId(),
                'url' => $render->getUrl(),
                'quality' => $render->getQuality(),
                'project_id' => $render->getProjectId(),
            ];
        }, $project->getRenders());

        return [
            'title' => $project->getLabel(),
            'description' => $project->getDescription(),
            'shortcode' => ProjectShortCodeFactory::generate($project),
            'thumbnail' => $project->getThumbnailPath(),
            'renders' => $renders,
        ];
    }
}
