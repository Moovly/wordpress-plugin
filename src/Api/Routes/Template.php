<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;

class Template extends Api
{
    use MoovlyApi;

    public $group = "templates";

    public function __construct()
    {
        parent::__construct();
        $this->registerMoovlyService();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/index', [
            'methods' => 'GET',
            'callback' => [$this, 'index'],
            'permission_callback' => [$this, 'index_permissions'],
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show'],
        ]);
    }

    public function index()
    {
        return $this->moovlyApi('getTemplates', function ($templates) {
            foreach ($templates as $template) {
                $template->identifier = $template->getId();
                $template->title = $template->getName();
                $template->shortcode = TemplateShortCodeFactory::generate($template);
            }

            return $templates;
        });
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        return $this->moovlyApi('getTemplate', $request->get_param('id'), function ($template) {
            return $this->mapTemplateToResponse($template);
        });
    }

    private function mapTemplateToResponse($template)
    {
        return [
            'id' => $template->getId(),
            'name' => $template->getName(),
            'variables' => $this->mapTemplateVariablesToResponse($template->getVariables()),
        ];
    }

    private function mapTemplateVariablesToResponse($templateVariables)
    {
        return collect($templateVariables)->map(function ($variable) {
            return [
                'id' => $variable->getId(),
                'weight' => $variable->getWeight(),
                'type' => $variable->getType(),
                'name' => $variable->getName(),
                'requirements' => $variable->getRequirements(),
            ];
        })->sortBy('weight');
    }
}
