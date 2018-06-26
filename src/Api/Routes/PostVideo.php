<?php
namespace Moovly\Api\Routes;

use WP_Query;
use Moovly\Api\Api;
use Moovly\Templates;
use Moovly\SDK\Model\Job;
use Moovly\Shortcodes\Factories\PostVideoShortCodeFactory;

class PostVideo extends Api
{
    public $group= "post-videos";

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
            'permission_callback' => [$this, 'index_permissions'],
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

    protected function getPostsWithVideo($request)
    {
        return collect((new WP_Query([
            'post_type' => 'post',
            'nopaging' => true,
            'meta_key' => Templates::$post_templates_job_key,
        ]))->posts)->each(function ($post) {
            $job = get_post_meta($post->ID, Templates::$post_templates_job_key);
            $job = $job[0];
            if ($job['job_id']) {
                $post->job = $this->moovlyApi('getJob', function ($job) {
                    return [
                        'id' => $job->getId(),
                        'status' => $job->getStatus(),
                        'values' => $this->mapJobValuesToResponse($job->getValues()),
                    ];
                }, function ($error) use ($job) {
                    return [
                        'id' =>  $job['job_id'],
                        'status' => $job['job_status'],
                        'values' => [],
                    ];
                });
            } else {
                $post->job = [
                    'id' =>  $job['job_id'],
                    'status' => $job['job_status'],
                    'values' => [],
                ];
            }
        });
    }

    private function mapJobValuesToResponse($values)
    {
        return collect(array_wrap($values))->map(function ($value) {
            return [
                'status' => $value->getStatus(),
                'url' => $value->getUrl(),
                'shortcode' => PostVideoShortCodeFactory::generate($value),
            ];
        });
    }
}
