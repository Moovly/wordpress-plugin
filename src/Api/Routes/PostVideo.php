<?php
namespace Moovly\Api\Routes;

use WP_Query;
use WP_Error;
use Moovly\Api\Api;
use Moovly\Templates;
use Moovly\SDK\Model\Job;
use Moovly\Api\Services\MoovlyApi;
use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;

class PostVideo extends Api
{
    use MoovlyApi;
    public $group= "post-videos";

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
        return $this->getPostsWithVideo($request)->map(function ($postWithVideo) {
            return [
                'title' => $postWithVideo->post_title,
                'url' => (get_edit_post_link($postWithVideo->ID, $htmlEncode = false)),
                'job' => $postWithVideo->job,
            ];
        });
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        $post = get_post($request->get_param('id'));

        if (!$post) {
            return new WP_Error(404, 'Post not found');
        }
        $job = get_post_meta($post->ID, Templates::$post_templates_job_key)[0];

        return $this->moovlyApi('getJob', $job['job_id'], function ($job) use ($post) {
            return [
                'id' => $job->getId(),
                'template' => $job->getTemplate(),
                'status' => $job->getStatus(),
                'values' => $this->mapJobValuesToResponse($job->getValues(), $post),
            ];
        }, function ($error) use ($job) {
            return [
                'id' =>  $job['job_id'],
                'template' => $job['job_template'],
                'status' => $job['job_status'],
                'values' => [],
            ];
        });
    }

    protected function getPostsWithVideo($request)
    {
        return collect((new WP_Query([
            'post_type' => 'post',
            'nopaging' => true,
            'meta_key' => Templates::$post_templates_job_key,
        ]))->posts)->each(function ($post) {
            $jobMeta = get_post_meta($post->ID, Templates::$post_templates_job_key)[0];
            $jobId = key_exists('job_id', $jobMeta) ? $jobMeta['job_id'] : '';

            $post->job = $this->moovlyApi('getJob', $jobId, function ($job) use ($post, $jobMeta) {
                update_post_meta($post->ID, Templates::$post_templates_job_key, [
                        'job_id' => $job->getId(),
                        'job_status' => $job->getStatus(),
                        'job_template' => $jobMeta['job_template'],
                    ]);

                return [
                        'id' => $job->getId(),
                        'template' => $jobMeta['job_template'],
                        'status' => $job->getStatus(),
                        'values' => $this->mapJobValuesToResponse($job->getValues(), $post),
                    ];
            }, function ($error) use ($jobMeta) {
                return [
                        'id' =>  $jobMeta['job_id'],
                        'template' => $jobMeta['job_template'],
                        'status' => $jobMeta['job_status'],
                        'values' => [],
                    ];
            });
        });
    }

    private function mapJobValuesToResponse($values, $post)
    {
        return collect(array_wrap($values))->map(function ($value) use ($post) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
                'shortcode' => PostVideoShortCodeFactory::generate($post),
            ];
        });
    }
}
