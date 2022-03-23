<?php

namespace Moovly\Api\Routes;

use WP_Error;
use Moovly\Api\Api;

class Auth extends Api
{
    public $group = "auth";

    protected static $auth_key;

    public function __construct()
    {
        parent::__construct();
        self::$auth_key = "{$this->domain}_access_token";
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public static function getToken()
    {
        return get_option(self::$auth_key) ?: '';
    }

    public function hasToken()
    {
        return (bool) $this->token();
    }

    public function deleteToken()
    {
        return delete_option(self::$auth_key);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/callback', [
            'methods' => 'GET',
            'callback' => [$this, 'callback'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);

        register_rest_route($this->namespace, '/token', [
            'methods' => 'GET',
            'callback' => [$this, 'token'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);

        register_rest_route($this->namespace, '/token', [
            'methods' => 'POST',
            'callback' => [$this, 'store'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);
    }

    public function callback($request)
    {
        $token = $request->get_param('token');

        if (is_null($token)) {
            return new WP_Error('rest_bad_request', __('Missing required access token'), ['status' => 400]);
        }

        update_option(self::$auth_key, $token);

        wp_redirect(admin_url("/admin.php?page=moovly-settings"), 301);
    }



    public function token()
    {
        return self::getToken();
    }



    public function store($request)
    {
        return update_option(self::$auth_key, $request->get_param('token'));
    }
}
