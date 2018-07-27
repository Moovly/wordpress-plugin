<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\SDK\Exception\BadRequestException;
use Moovly\SDK\Model\Variable;
use Moovly\Templates;
use Moovly\Api\Routes\Job;
use Illuminate\Support\Str;
use Moovly\Api\Services\MoovlyApi;
use Moovly\SDK\Factory\JobFactory;
use Moovly\SDK\Factory\ValueFactory;
use Moovly\SDK\Model\Template as TemplateModel;
use Moovly\Shortcodes\Factories\TemplateShortCodeFactory;
use Ramsey\Uuid\Uuid;
use WP_Error;

class Template extends Api
{
    const TEMPLATE_ERROR_400_STRIPPED = 'The API call you made resulted in a Bad Request response (HTTP 400). ' .
        'The reason given by the server: Object: '
    ;

    use MoovlyApi;

    public $group = "templates";

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
        try {
            $templates = $this->getMoovlyService()->getTemplates();
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return array_map(function (TemplateModel $template) {
            $result = new \stdClass();

            $result->identifier = $template->getId();
            $result->title = $template->getName();
            $result->shortcode = TemplateShortCodeFactory::generate($template);
            $result->thumbnail = $template->getThumbnail();
            $result->preview = $template->getPreview();

            return $result;
        }, $templates);
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    public function show($request)
    {
        try {
            $template = $this->getMoovlyService()->getTemplate($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $this->mapTemplateToResponse($template);
    }

    public function store($request)
    {
        try {
            $template = $this->getMoovlyService()->getTemplate($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $this->createTemplateJobFromRequest($template, $request);
    }

    public function settings($request)
    {
        if ($request->get_method() !== 'POST') {
            return [
                'post_templates' => get_option(Templates::$post_templates_key) ?: [],
            ];
        }

        $templates = collect($request->get_param('post_templates'))->map(function ($templateId) {
            try {
                $template = $this->getMoovlyService()->getTemplate($templateId);
            } catch (\Exception $e) {
                return $this->throwWPError(null, $e);
            }

            return $this->mapTemplateToResponse($template);
        })->toArray();

        if (is_array($templates)) {
            update_option(Templates::$post_templates_key, $templates);
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
     * @throws
     */
    private function createTemplateJobFromRequest($template, $request)
    {
        $name = "Moovly Wordpress Plugin: {$template->getName()}, " . date('d/m/Y');
        $name = is_null($request->get_param('name')) ? $name : $request->get_param('name');

        $job = JobFactory::create([
            ValueFactory::create(
                (string) Uuid::uuid4(),
                $name,
                collect($request->get_param('variables'))->mapWithKeys(function ($variable) {
                    return $variable;
                })->toArray()),
            ])->setTemplate($template)
        ->setOptions([
            'create_moov' => Job::savesProjects(),
        ]);

        try {
            $job = $this->getMoovlyService()->createJob($job);
        } catch (BadRequestException $bre) {
            $message = str_replace(self::TEMPLATE_ERROR_400_STRIPPED, '', $bre->getMessage());

            return new WP_Error($bre->getCode(), $message, ['status' => $bre->getCode()]);
        }

        return [
            'job_id' => $job->getId(),
            'options' => $job->getOptions(),
        ];
    }
}
