<?php

namespace Moovly;

use WP_Error;

class Auth
{
    protected $version = "v1";

    protected $namespace;

    protected $auth_key = 'moovly_access_token';

    public function __construct()
    {
        $this->namespace = "moovly/{$this->version}/auth";
        add_action('rest_api_init', [$this, 'registerEndPoints']);
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
    }

    public function callback($request)
    {
        $token = $request->get_param('token');

        if (is_null($token)) {
            return new WP_Error('rest_bad_request', __('Missing required access token'), ['status' => 400]);
        }

        update_option($this->auth_key, $token);

        wp_redirect(admin_url("/admin.php?page=moovly"), 301);
    }

    public function callback_permissions()
    {
        return current_user_can('manage_options');
    }

    public function token()
    {
        return get_option($this->auth_key);
    }

    public function token_permissions()
    {
        return current_user_can('manage_options');
    }
}
