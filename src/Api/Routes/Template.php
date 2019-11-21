<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Transformers\TemplateTransformer;
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
        'The reason given by the server: Object: ';

    const TEMPLATE_EMAIL_KEY                  = 'email_address';

    const WORDPRESS_POST_CONTENT_TEMPLATE_KEY = 'post_content';
    const WORDPRESS_POST_TITLE_TEMPLATE_KEY   = 'post_title';
    const WORDPRESS_POST_NAME_TEMPLATE_KEY    = 'post_name';

    const WORDPRESS_TEMPLATE_KEYS = [
        self::WORDPRESS_POST_CONTENT_TEMPLATE_KEY,
        self::WORDPRESS_POST_TITLE_TEMPLATE_KEY,
        self::WORDPRESS_POST_NAME_TEMPLATE_KEY,
    ];

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

            $isPostAutomation = $this->doesTemplateHaveWordPressFields($template);
            $isEmail = $this->doesTemplateHaveEmailCollect($template);

            $result->identifier = $template->getId();
            $result->title = $template->getName();
            $result->shortcode = TemplateShortCodeFactory::generate($template);
            $result->thumbnail = $template->getThumbnail();
            $result->preview = $template->getPreview();
            $result->supports_post_automation = $isPostAutomation && !$isEmail;
            $result->is_email_enabled = $isEmail;

            return $result;
        }, $templates);
    }

    public function index_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return array
     */
    public function show($request)
    {
        try {
            $template = $this->getMoovlyClient()->getTemplate($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $template;
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return array
     */
    public function store($request)
    {
        try {
            $template = $this->getMoovlyService()->getTemplate($request->get_param('id'));
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $this->createTemplateJobFromRequest($template, $request);
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return array
     */
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

            return TemplateTransformer::transform($template);
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
     * @param $request
     *
     * @return array
     *
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
                })->toArray()
            ),
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

    /**
     * @param TemplateModel $template
     *
     * @return bool
     */
    private function doesTemplateHaveWordPressFields(TemplateModel $template)
    {
        $postContentTargets = array_filter($template->getVariables(), function (Variable $variable) {
            return $variable->getName() === self::WORDPRESS_POST_CONTENT_TEMPLATE_KEY;
        });

        $postNameTargets = array_filter($template->getVariables(), function (Variable $variable) {
            $isTitle = $variable->getName() === self::WORDPRESS_POST_TITLE_TEMPLATE_KEY;
            $isName = $variable->getName() === self::WORDPRESS_POST_NAME_TEMPLATE_KEY;

            return $isTitle || $isName;
        });

        if (count($postContentTargets) === 1 && count($postNameTargets) >= 1) {
            return true;
        }

        return false;
    }

    /**
     * @param TemplateModel $template
     *
     * @return bool
     */
    private function doesTemplateHaveEmailCollect(TemplateModel $template)
    {
        $emailTargets = array_filter($template->getVariables(), function (Variable $variable) {
            return $variable->getName() === self::TEMPLATE_EMAIL_KEY;
        });

        if (count($emailTargets) === 1) {
            return true;
        }

        return false;
    }
}
