<?php

namespace Moovly\Api\Routes;

use WP_Query;
use WP_Error;
use Moovly\Api\Api;
use Moovly\Templates;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;

class PostVideo extends Api
{
    use MoovlyApi;

    public $group = "post-videos";

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
            'permission_callback' => [$this, 'can_manage_options'],
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function index($request)
    {
        return array_map(function ($postWithVideo) {
            return [
                'title' => $postWithVideo->post_title,
                'url' => (get_edit_post_link($postWithVideo->ID, $htmlEncode = false)),
                'job' => $postWithVideo->job,
            ];
        }, $this->getPostsWithVideo($request));
    }


    public function show($request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post) {
            return new WP_Error(404, 'Post not found');
        }
        $job = get_post_meta($post->ID, Templates::$post_templates_job_key)[0];

        try {
            $job = $this->getMoovlyService()->getJob($job['job_id']);
        } catch (\Exception $e) {
            return [
                'id' =>  $job['job_id'],
                'template' => $job['job_template'],
                'status' => $job['job_status'],
                'values' => [],
            ];
        }

        return [
            'id' => $job->getId(),
            'template' => $job->getTemplate(),
            'status' => $job->getStatus(),
            'values' => $this->mapJobValuesToResponse($job->getValues(), $post),
        ];
    }

    protected function getPostsWithVideo($request)
    {
        $posts = new WP_Query([
            'post_type' => 'post',
            'nopaging' => true,
            'meta_key' => Templates::$post_templates_job_key,
        ]);

        $result = [];

        foreach ($posts->posts as &$post) {
            $jobMeta = get_post_meta($post->ID, Templates::$post_templates_job_key)[0];

            if (empty($jobMeta)) {
                continue;
            }

            $jobId = key_exists('job_id', $jobMeta) ? $jobMeta['job_id'] : '';

            try {
                $job = $this->getMoovlyService()->getJob($jobId);
            } catch (\Exception $e) {
                $post->job = [
                    'id' =>  $jobMeta['job_id'],
                    'template' => $jobMeta['job_template'],
                    'status' => $jobMeta['job_status'],
                    'values' => [],
                ];

                $result[] = $post;

                continue;
            }

            update_post_meta($post->ID, Templates::$post_templates_job_key, [
                'job_id' => $job->getId(),
                'job_status' => $job->getStatus(),
                'job_template' => $jobMeta['job_template'],
            ]);

            $post->job = [
                'id' => $job->getId(),
                'template' => $jobMeta['job_template'],
                'status' => $job->getStatus(),
                'values' => $this->mapJobValuesToResponse($job->getValues(), $post),
            ];

            $result[] = $post;
        }

        return $result;
    }

    private function mapJobValuesToResponse($values, $post)
    {
        return array_map(function ($value) use ($post) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
                'shortcode' => PostVideoShortCodeFactory::generate($post),
            ];
        }, $values);
    }
}