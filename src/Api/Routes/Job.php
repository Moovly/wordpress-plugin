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

    public static $email_form_submission;

    public function __construct()
    {
        parent::__construct();
        self::$save_projects_key = "{$this->domain}_jobs_create_moov";
        self::$quality_key = "{$this->domain}_jobs_quality";
        self::$email_form_submission = "{$this->domain}_email_form_submission";
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

    public static function getEmailFormSubmission()
    {
        return get_option(self::$email_form_submission) ?: null;
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/(?P<id>[^/]+)/status', [
            'methods' => 'GET',
            'callback' => [$this, 'status'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, '/settings', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'settings'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);
    }

    public function status($request)
    {
        try {
            $job = $this->getMoovlyService()->getJob($request->get_param('id'));
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
            $email_form_submission = $request->get_param('email_form_submission') ?: null;
            update_option(self::$email_form_submission, $email_form_submission);
        }

        return [
            'create_moov' => get_option(self::$save_projects_key),
            'quality' => self::getQuality(),
            'email_form_submission' => get_option(self::$email_form_submission),
        ];
    }


    private function mapValuesToResponse($values)
    {
        return array_map(function ($value) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
            ];
        }, $values);
    }
}
