<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\SDK\Model\Variable;
use Moovly\Templates;
use Moovly\Api\Routes\Job;
use Illuminate\Support\Str;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;
use Moovly\SDK\Model\Template as TemplateModel;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;

class Template extends Api
{
    use MoovlyApi;

    public $group = "templates";

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

        register_rest_route($this->namespace, '/settings', [
            'methods' => ['GET', 'POST'],
            'callback' => [$this, 'settings'],
            'permission_callback' => [$this, 'settings_permissions'],
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show'],
        ]);

        register_rest_route($this->namespace, '/(?P<id>[^/]+)/store', [
            'methods' => 'POST',
            'callback' => [$this, 'store'],
        ]);
    }

    public function index()
    {
        return $this->moovlyApi('getTemplates', function ($templates) {
            return array_map(function (TemplateModel $template) {
                $result = new \stdClass();

                $result->identifier = $template->getId();
                $result->title = $template->getName();
                $result->shortcode = TemplateShortCodeFactory::generate($template);
                $result->thumbnail = $template->getThumbnail();
                $result->preview = $template->getPreview();

                return $result;
            }, $templates);
        });
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        return $this->moovlyApi('getTemplate', $request->get_param('id'), function ($template) {
            return $this->mapTemplateToResponse($template);
        });
    }

    public function store($request)
    {
        return $this->moovlyApi('getTemplate', $request->get_param('id'), function ($template) use ($request) {
            return $this->createTemplateJobFromRequest($template, $request);
        });
    }

    public function settings($request)
    {
        if ($request->get_method() === 'POST') {
            $templates = collect($request->get_param('post_templates'))->map(function ($templateId) {
                return $this->moovlyApi('getTemplate', $templateId, function ($template) {
                    return $this->mapTemplateToResponse($template);
                });
            })->toArray();
            if (is_array($templates)) {
                update_option(Templates::$post_templates_key, $templates);
            }
        }

        return [
            'post_templates' => get_option(Templates::$post_templates_key) ?: [],
        ];
    }

    public function settings_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * @param TemplateModel $template
     * @return array
     */
    private function mapTemplateToResponse($template)
    {
        return [
            'id' => $template->getId(),
            'name' => $template->getName(),
            'thumbnail' => $template->getThumbnail(),
            'preview' => [
                'show' => true,
                'url' => $template->getPreview(),
            ],
            'variables' => $this->mapTemplateVariablesToResponse($template->getVariables()),
        ];
    }

    private function mapTemplateVariablesToResponse($templateVariables)
    {
        return collect($templateVariables)->map(function ($variable) {
            /** @var Variable $variable */
            return [
                'id' => $variable->getId(),
                'weight' => $variable->getWeight(),
                'type' => $variable->getType(),
                'name' => $variable->getName(),
                'requirements' => $variable->getRequirements(),
            ];
        })->sortBy('weight')->values();
    }

    /**
     * @param TemplateModel $template
     * @param $request
     * @return \WP_Error
     */
    private function createTemplateJobFromRequest($template, $request)
    {
        $name = "Moovly Wordpress Plugin: {$template->getName()}, " . date('d/m/Y');
        $name = is_null($request->get_param('name')) ? $name : $request->get_param('name');

        $job = JobFactory::create([
            ValueFactory::create(
                Str::uuid(),
                $name,
                collect($request->get_param('variables'))->mapWithKeys(function ($variable) {
                    return $variable;
                })->toArray()),
            ])->setTemplate($template)
        ->setOptions([
            'create_moov' => Job::savesProjects(),
        ]);

        return $this->moovlyApi('createJob', $job, function ($job) {
            /** @var \Moovly\SDK\Model\Job $job */
            return [
                'job_id' => $job->getId(),
                'options' => $job->getOptions(),
            ];
        });
    }
}
