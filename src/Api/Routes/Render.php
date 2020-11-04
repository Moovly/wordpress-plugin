<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;


/**
 * Class Project
 * @package Moovly\Api\Routes
 */
class Render extends Api
{
    use MoovlyApi;

    /**
     * @var string
     */
    public $group = "renders";

    /**
     * Project constructor.
     */
    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    /**
     * @return void
     */
    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/generated/index', [
            'methods' => 'GET',
            'callback' => [$this, 'generatedIndex'],
        ]);
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error
     */
    public function generatedIndex()
    {
        try {
            $renders = $this->getMoovlyService()->getRendersForUser('generated');
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }

        return $renders;
    }

   
   
}