<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Routes\Render;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\ProjectShortCodeFactory;
use Moovly\Shortcodes\Factories\ProjectsShortCodeFactory;
use Moovly\Shortcodes\Factories\RendersShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

/**
 * Class Project
 * @package Moovly\Api\Routes
 */
class Project extends Api
{
    use MoovlyApi, PermissionTrait;

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
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show'],
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)/renders', [
            'methods' => 'GET',
            'callback' => [$this, 'projectRenders'],
        ]);
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function index($request)
    {
        if (!$this->index_permissions()) {
            $this->checkShortcodePermission(ProjectsShortCodeFactory::$tag);
        }
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        $pageSize = $request->get_param('page_size') ? intval($request->get_param('page_size')) : 25;

        try {
            $response = $this->getMoovlyService()->getProjects('unarchived', ['renders'], $page, $pageSize);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }


        return array_map(function ($project) {
            return $this->transform($project);
        }, $response);
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
        $this->checkShortcodePermission(ProjectShortCodeFactory::$tag);
        try {
            $project = $this->getMoovlyService()->getProject($request->get_param('id'), ['renders']);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $this->transform($project);
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function projectRenders($request)
    {
        $this->checkShortcodePermission(RendersShortCodeFactory::$tag);
        try {
            $project = $this->getMoovlyService()->getProject($request->get_param('id'), ['renders']);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return array_map(function ($render) {
            return Render::transform($render);
        }, $project->getRenders());
    }


    /**
     * @param \Moovly\SDK\Model\Project $project
     *
     * @return array
     */
    private function transform($project)
    {

        $renders = $project->getRenders();
        usort(
            $renders,
            function ($a, $b) {
                return strtotime($a->getDateFinished()) - strtotime($b->getDateFinished());
            }
        );
        $lastRender = $renders[0] ?? null;

        return [
            'title' => $project->getLabel(),
            'description' => $project->getDescription(),
            'shortcode' => ProjectShortCodeFactory::generate($project),
            'thumbnail' => $project->getThumbnailPath(),
            'last_render_url' => $lastRender ? $lastRender->getUrl() : null,
        ];
    }
}