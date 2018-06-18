<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;
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

        register_rest_route($this->namespace, '/(?P<id>[^/]+)/store', [
            'methods' => 'POST',
            'callback' => [$this, 'store'],
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

    public function store($request)
    {
        return $this->moovlyApi('getTemplate', $request->get_param('id'), function ($template) use ($request) {
            return $this->createTemplateJobFromRequest($template, $request);
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
        })->sortBy('weight')->values();
    }

    private function createTemplateJobFromRequest($template, $request)
    {
        $job = JobFactory::create([
                ValueFactory::create('external_wp_moovly_plugin_id_1', 'Moovly plugin job', collect($request->get_param('variables'))->mapWithKeys(function ($variable) {
                    return $variable;
                })->toArray()),
        ])->setTemplate($template);

        return $this->moovlyApi('createJob', $job, function ($job) {
            return [
                'job_id' => $job->getId(),
            ];
        });
    }
}
