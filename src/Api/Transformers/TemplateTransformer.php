<?php

namespace Moovly\Api\Transformers;

use Moovly\SDK\Model\Template;
use Moovly\SDK\Model\Variable;

class TemplateTransformer
{
    /**
     * @param Template $template
     * @return array
     */
    public static function transform($template)
    {
        return [
            'id' => $template->getId(),
            'name' => $template->getName(),
            'thumbnail' => $template->getThumbnail(),
            'preview' => [
                'show' => true,
                'url' => $template->getPreview(),
            ],
            'variables' => self::transformVariables($template),
        ];
    }

    /**
     * @param Template $template
     *
     * @return array
     */
    public static function transformVariables($template)
    {
        return collect($template->getVariables())->map(function ($variable) {
            /** @var Variable $variable */
            return [
                'id' => $variable->getId(),
                'weight' => $variable->getWeight(),
                'type' => $variable->getType(),
                'name' => $variable->getName(),
                'requirements' => $variable->getRequirements(),
            ];
        })->sortBy('weight')->values();
    }
}