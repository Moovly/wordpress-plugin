<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Api\Transformers\ProjectTransformer;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;

/**
 * Class Project
 * @package Moovly\Api\Routes
 */
class Project extends Api
{
    use MoovlyApi;

    /**
     * @var string
     */
    public $group = "projects";

    /**
     * Project constructor.
     */
    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    /**
     * @return void
     */
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

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function index($request)
    {
        try {
            $projects = $this->getMoovlyService()->getProjects();
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return array_map(function ($project) {
            return ProjectTransformer::transform($project);
        }, $projects);
    }

    /**
     * @return bool
     */
    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function show($request)
    {
        try {
            $project = $this->getMoovlyService()->getProject($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return ProjectTransformer::transform($project);
    }
}
