<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\RendersShortCodeFactory;
use Moovly\Shortcodes\Traits\PermissionTrait;

/**
 * Class Project
 * @package Moovly\Api\Routes
 */
class Render extends Api
{
    use MoovlyApi, PermissionTrait;

    /**
     * @var string
     */
    public $group = "renders";

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
        register_rest_route($this->namespace, '/generated/index', [
            'methods' => 'GET',
            'callback' => [$this, 'generatedIndex'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function generatedIndex($request)
    {
        $this->checkShortcodePermission(RendersShortCodeFactory::$tag);
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
        $pageSize = $request->get_param('page_size') ? intval($request->get_param('page_size')) : 25;
        try {
            $renders = $this->getMoovlyService()->getRendersForUser('generated', $page, $pageSize);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return [
            'results' =>  array_map(function ($render) {
                return $this->transform($render);
            }, $renders['renders']),
            'count' => $renders['count']
        ];
    }


    /**
     * @param array \Moovly\SDK\Model\Render $render
     *
     * @return array
     */
    public static function transform($render)
    {

        return [
            'id' => $render->getId(),
            'finished_at' => $render->getDateFinished()->format(DATE_ATOM),
            'video_url' => $render->getUrl(),
            'quality' => $render->getQuality(),
            'width' => $render->getWidth(),
            'height' => $render->getHeight(),
            'thumbnail' => $render->getThumbnail(),
        ];
    }
}
