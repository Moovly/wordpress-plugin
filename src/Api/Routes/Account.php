<?php

namespace Moovly\Api\Routes;

use Moovly\Api\Api;
use Moovly\Api\Services\MoovlyApi;

class Account extends Api
{
    use MoovlyApi;

    public $group= "account";

    public function __construct()
    {
        parent::__construct();
        $this->registerMoovlyService();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/me', [
            'methods' => 'GET',
            'callback' => [$this, 'me'],
            'permission_callback' => [$this, 'me_permissions'],
        ]);
    }

    public function me()
    {
        return $this->call('getCurrentUser', function ($user) {
            return $user;
        });
    }

    public function me_permissions()
    {
        return current_user_can('manage_options');
    }
}
