<?php

namespace Moovly\Shortcodes\Traits;


trait PermissionTrait
{

    public $permissionShortcodeGroup = 'permission.shortcode';


    public function setDefaultPermissionWhenNotExist($permission, $value, $group = null)
    {
        if ($group) {
            $option = get_option($group, null);
            if ($option == null) {
                update_option($group, [$permission => $value]);
            } else {
                if (!isset($option[$permission])) {
                    $option[$permission] = $value;
                    update_option($group, $option);
                }
            }
        } else {
            if (get_option($permission, null) == null) {
                update_option($permission, $value);
            }
        }
    }

    public function updatePermission($permission, $value)
    {
        update_option($permission, $value);
    }

    public function checkShortcodePermission($permission, $htmlOuput = false)
    {

        $values = get_option($this->permissionShortcodeGroup);
        $valid = $values && isset($values[$permission]) && $values[$permission];
        if (!$valid && $htmlOuput) {
            return "<div><p>This shortcode is not enabled in the Moovly plugin settings</p></div>";
        }
        if (!$valid) {
            return wp_send_json_error('No permission', 403);
        }
    }




    public function shortcodePermissions()
    {
        return get_option($this->permissionShortcodeGroup);
    }
}