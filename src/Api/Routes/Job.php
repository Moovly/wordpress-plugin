<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Job extends Api
{
    use MoovlyApi;

    public $group = "jobs";

    public static $save_projects_key;

    public function __construct()
    {
        parent::__construct();
        $this->registerMoovlyService();
        self::$save_projects_key = "{$this->domain}_jobs_create_moov";
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public static function savesProjects()
    {
        return (bool) get_option(self::$save_projects_key);
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
        return $this->moovlyApi('getJob', $request->get_param('id'), function ($job) {
            return [
                'id' => $job->getId(),
                'status' => $job->getStatus(),
                'values' => $this->mapValuesToResponse($job->getValues()),
            ];
        });
    }

    public function settings($request)
    {
        if ($request->get_method() === 'POST') {
            update_option(self::$save_projects_key, $request->get_param('create_moov'));
        }

        return get_option(self::$save_projects_key);
    }

    public function settings_permissions()
    {
        return current_user_can('manage_options');
    }

    private function mapValuesToResponse($values)
    {
        return collect(array_wrap($values))->map(function ($value) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
            ];
        });
    }
}
