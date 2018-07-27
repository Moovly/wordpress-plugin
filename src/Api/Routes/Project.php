<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;

class Project extends Api
{
    use MoovlyApi;

    public $group = "projects";

    public function __construct()
    {
        parent::__construct();
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

    public function index($request)
    {
        try {
            $projects = $this->getMoovlyService()->getProjects();
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return array_map(function ($project) {
            return $this->mapProjectToResponse($project);
        }, $projects);
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        try {
            $project = $this->getMoovlyService()->getProject($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $this->mapProjectToResponse($project);
    }

    private function mapProjectToResponse($project)
    {
        return [
            'title' => $project->getLabel(),
            'description' => $project->getDescription(),
            'shortcode' => ProjectShortCodeFactory::generate($project),
            'thumbnail' => $project->getThumbnailPath(),
            'renders' => $this->mapRendersToResponse($project->getRenders()),
        ];
    }

    private function mapRendersToResponse($renders)
    {
        return array_map(function ($render) {
            return [
            'id' => $render->getId(),
                'url' => $render->getUrl(),
                'quality' => $render->getQuality(),
                'project_id' => $render->getProjectId(),
            ];
        }, array_wrap($renders));
    }
}
