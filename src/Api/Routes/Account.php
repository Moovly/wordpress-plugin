<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Account extends Api
{
    use MoovlyApi;

    public $group = "accounts";

    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/me', [
            'methods' => 'GET',
            'callback' => [$this, 'me'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);
    }

    public function me()
    {
        try {
            return $this->getMoovlyService()->getCurrentUser();
        } catch (\Exception $e) {
            return $this->throwWPError(null, $e);
        }
    }
}
