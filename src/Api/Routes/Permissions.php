<?php

namespace Moovly\Api\Routes;

use WP_Error;
use Moovly\Api\Api;
use Moovly\Shortcodes\Traits\PermissionTrait;

class Permissions extends Api
{
    use PermissionTrait;
    public $group = "permissions";

    public function __construct()
    {
        parent::__construct();
        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints()
    {

        register_rest_route($this->namespace, '/shortcodes', [
            'methods' => 'GET',
            'callback' => [$this, 'getShortcodePermissions'],
        ]);
        register_rest_route($this->namespace, '/shortcodes', [
            'methods' => 'PUT',
            'callback' => [$this, 'updateShortcodePermissions'],
        ]);
    }

    public function getShortcodePermissions()
    {
        return $this->shortcodePermissions();
    }


    public function updateShortcodePermissions($request)
    {
        print_r($request->get_param('permissions'));
        return $this->updatePermission($this->permissionShortcodeGroup, $request->get_param('permissions'));
    }
}