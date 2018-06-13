<?php

namespace Moovly\Api;

use WP_Error;

class Auth
{
    protected $namespace;

    protected static $auth_key;

    public function __construct($domain, $version)
    {
        self::$auth_key = "{$domain}_access_token";
        $this->namespace = "{$domain}/{$version}/auth";
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public static function getToken()
    {
        return get_option(self::$auth_key);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/callback', [
            'methods' => 'GET',
            'callback' => [$this, 'callback'],
            'permission_callback' => [$this, 'callback_permissions'],
        ]);

        register_rest_route($this->namespace, '/token', [
            'methods' => 'GET',
            'callback' => [$this, 'token'],
            'permission_callback' => [$this, 'token_permissions'],
        ]);

        register_rest_route($this->namespace, '/logout', [
            'methods' => 'POST',
            'callback' => [$this, 'logout'],
            'permission_callback' => [$this, 'logout_permissions'],
        ]);
    }

    public function callback($request)
    {
        $token = $request->get_param('token');

        if (is_null($token)) {
            return new WP_Error('rest_bad_request', __('Missing required access token'), ['status' => 400]);
        }

        update_option(self::$auth_key, $token);

        wp_redirect(admin_url("/admin.php?page=moovly"), 301);
    }

    public function callback_permissions()
    {
        return current_user_can('manage_options');
    }

    public function token()
    {
        return self::getToken();
    }

    public function token_permissions()
    {
        return current_user_can('manage_options');
    }

    public function logout()
    {
        return update_option(self::$auth_key, null);
    }

    public function logout_permissions()
    {
        return current_user_can('manage_options');
    }
}
