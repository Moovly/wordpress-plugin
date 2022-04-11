<?php

namespace Moovly\Api\Routes;

use WP_Error;
use Moovly\Api\Api;
use Moovly\Shortcodes\Traits\SettingTrait;

class Settings extends Api
{
    use SettingTrait;
    public $group = "settings";

    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {
        register_rest_route($this->namespace, '/all', [
            'methods' => 'GET',
            'callback' => [$this, 'getCurrentSettings'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);
        register_rest_route($this->namespace, '/update', [
            'methods' => 'PUT',
            'callback' => [$this, 'updateSettings'],
            'permission_callback' => [$this, 'can_manage_options'],
        ]);
    }

    public function getCurrentSettings()
    {
        return $this->getAllSettings();
    }


    public function updateSettings($request)
    {
        if ($request->get_param('locale')) {
            return $this->saveUpdateSetting($this->localeSettingKey, $request->get_param('locale'));
        }
    }
}