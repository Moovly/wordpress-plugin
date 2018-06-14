<?php

namespace Moovly;

use Moovly\Api\Api;
use Moovly\Settings;
use Moovly\Shortcodes\Shortcodes;

class Moovly
{
    public $version;

    public $settings;

    public $api;

    public function __construct()
    {
        $this->shortcodes = new Shortcodes;
        $this->settings = new Settings;
        $this->api = new Api;
    }

    public function initialize()
    {
        $this->api->register();
        $this->shortcodes->register();
        $this->version = get_plugin_data(__DIR__ . '/../moovly.php')['Version'];
        add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        add_action('admin_menu', [$this, 'addMenuItems']);
    }

    public function registerAdminAssets($page)
    {
        if (strpos($page, 'moovly') !== false) {
            wp_enqueue_style('moovly', plugins_url("moovly/dist/moovly.css"), $dependencies = [], $this->version, $media = 'all');
            wp_register_script('moovly', plugins_url("moovly/dist/moovly-plugin.js"), $dependencies = [], $this->version, $in_footer = true);
            wp_localize_script('moovly', 'moovlyApiSettings', [
                'root' => esc_url_raw(rest_url($this->api->domain)),
                'version' => $this->api->version,
                'nonce' => wp_create_nonce('wp_rest'),
            ]);

            wp_enqueue_script('moovly');
        }
    }

    public function registerAssets()
    {
        wp_register_script('moovly', plugins_url("moovly/dist/moovly.js"), $dependencies = [], $this->version, $in_footer = true);
        wp_localize_script('moovly', 'moovlyApiSettings', [
            'root' => esc_url_raw(rest_url($this->api->domain)),
            'version' => $this->api->version,
            'nonce' => wp_create_nonce('wp_rest'),
        ]);

        wp_enqueue_script('moovly');
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
