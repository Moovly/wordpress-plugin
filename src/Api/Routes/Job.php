<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Job extends Api
{
    use MoovlyApi;

    public $group = "jobs";

    public function __construct()
    {
        parent::__construct();
        $this->registerMoovlyService();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/(?P<id>[^/]+)/status', [
            'methods' => 'GET',
            'callback' => [$this, 'status'],
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
