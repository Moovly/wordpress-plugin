<?php

namespace Moovly;

use Moovly\Settings;

class Moovly
{
    public function initialize()
    {
        $this->addMenuItems();
    }

    private function addMenuItems()
    {
        add_action('admin_menu', function () {
            add_menu_page(
                __('Moovly', 'moovly'),
                __('Moovly', 'moovly'),
                'manage_options',
                'moovly',
                function () {
                    return Settings::make();
                }
            );
        });
    }
}
