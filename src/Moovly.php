<?php

namespace Moovly;

use Moovly\Settings;

class Moovly
{
    public $version;

    public $settings;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function initialize()
    {
        $this->settings->initialize();
        $this->version = get_plugin_data(__DIR__ . '/../moovly.php')['Version'];
        add_action('admin_enqueue_scripts', [$this, 'registerAssets']);
        add_action('admin_menu', [$this, 'addMenuItems']);
    }

    public function registerAssets($page)
    {
        if (strpos($page, 'moovly') !== false) {
            wp_enqueue_style('moovly', plugins_url("moovly/dist/moovly.css"), $dependencies = [], $this->version, $media = 'all');
            wp_register_script('moovly', plugins_url("moovly/dist/moovly.js"), $dependencies = [], $this->version, $in_footer = true);
            wp_localize_script('moovly', 'moovlyApiSettings', [
                'root' => esc_url_raw(rest_url('/moovly')),
                'nonce' => wp_create_nonce('wp_rest'),
            ]);
            wp_enqueue_script('moovly');
        }
    }

    public function addMenuItems()
    {
        add_menu_page(
            __('Moovly', 'moovly'),
            __('Moovly', 'moovly'),
            'manage_options',
            'moovly',
            function () {
                return $this->settings->makeView();
            }
        );
    }
}
