<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Job extends Api
{
    use MoovlyApi;

    public $group = "jobs";

    public static $save_projects_key;

    public static $quality_key;

    public function __construct()
    {
        parent::__construct();
        self::$save_projects_key = "{$this->domain}_jobs_create_moov";
        self::$quality_key = "{$this->domain}_jobs_quality";
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public static function savesProjects()
    {
        return (bool) get_option(self::$save_projects_key);
    }

    public static function getQuality()
    {
        return get_option(self::$quality_key) ?: '480p';
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/(?P<id>[^/]+)/status', [
            'methods' => 'GET',
            'callback' => [$this, 'status'],
        ]);

        register_rest_route($this->namespace, '/settings', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'settings'],
            'permission_callback' => [$this, 'settings_permissions'],
        ]);
    }

    public function status($request)
    {
        try {
            $id = $request->get_param('id');

            if (empty($id)) {
                throw new \Exception('Id not found in request');

            }
            $job = $this->getMoovlyService()->getJob($id);
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        $response = [
            'id' => $job->getId(),
            'status' => $job->getStatus(),
            'values' => $this->mapValuesToResponse($job->getValues()),
        ];

        $result = new \WP_REST_Response($response, 200);
        $result->set_headers(array('Cache-Control' => 'no-cache'));
        return $result;
    }

    public function settings($request)
    {
        if ($request->get_method() === 'POST') {
            update_option(self::$save_projects_key, $request->get_param('create_moov'));
            update_option(self::$quality_key, $request->get_param('quality'));
        }

        return [
            'create_moov' => get_option(self::$save_projects_key),
            'quality' => self::getQuality(),
        ];
    }

    public function settings_permissions()
    {
        return current_user_can('manage_options');
    }

    private function mapValuesToResponse($values)
    {
        return array_map(function ($value) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
            ];
        }, array_wrap($values));
    }
}
