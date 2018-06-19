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

    public function index($request)
    {
        return $this->moovlyApi('getProjects', null, function ($projects) {
            return collect(array_wrap($projects))->map(function ($project) {
                return $this->mapProjectToResponse($project);
            });
        });
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        return $this->moovlyApi('getProject', $request->get_param('id'), function ($template) {
            return $this->mapProjectToResponse($template);
        });
    }

    private function mapProjectToResponse($project)
    {
        return [
            'title' => $project->getLabel(),
            'description' => $project->getDescription(),
            'shortcode' => ProjectShortCodeFactory::generate($project),
            'thumbnail' => $project->getThumbnailPath(),
        ];
    }
}
