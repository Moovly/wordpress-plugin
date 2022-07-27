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
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)/renders', [
            'methods' => 'GET',
            'callback' => [$this, 'projectRenders'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route($this->namespace, '/(?P<id>[^/]+)/renders/(?P<render_id>[^/]+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'deleteProjectRender'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function index($request)
    {
        if (!$this->can_manage_options()) {
            $this->checkShortcodePermission(ProjectsShortCodeFactory::$tag);
        }


        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        $pageSize = $request->get_param('page_size') ? intval($request->get_param('page_size')) : 25;

        try {
            $response = $this->getMoovlyService()->getProjects('unarchived', ['renders', 'stage-settings'], $page, $pageSize);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }


        return array_map(function ($project) {
            return $this->transform($project);
        }, $response);
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
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function deleteProjectRender($request)
    {

        $this->checkShortcodePermission(RendersShortCodeFactory::$tag);
        try {
            $this->getMoovlyService()->deleteRender($request->get_param('render_id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return ["success" => true];
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
            'id' => $project->getId(),
            'title' => $project->getLabel(),
            'description' => $project->getDescription(),
            'shortcode' => ProjectShortCodeFactory::generate($project),
            'thumbnail' => $project->getThumbnailPath(),
            'last_render_url' => $lastRender ? $lastRender->getUrl() : null,
            'stage' => $project->getStage()
        ];
    }
}
