<?php

namespace Moovly;

use Moovly\Api\Api;
use Moovly\Projects;
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
        $this->templates = new Templates;
        $this->projects = new Projects;
        $this->settings = new Settings;
        $this->api = new Api;
    }

    public function initialize()
    {
        $this->api->register();
        $this->shortcodes->register();
        $this->version = get_file_data(__DIR__ . '/../moovly.php', [
            'Version' => 'Version',
        ])['Version'];
        add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        add_action('admin_menu', [$this, 'addMenuItems']);

        return $this;
    }

    public function terminate()
    {
        $this->api->auth->deleteToken();
    }

    public function registerAdminAssets($page)
    {
        if (strpos($page, 'moovly') !== false) {
            add_filter('admin_body_class', function ($classes) {
                return "$classes moovly-plugin";
            });
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
        wp_enqueue_style('moovly', plugins_url("moovly/dist/moovly.css"), $dependencies = [], $this->version, $media = 'all');
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
            'moovly-settings',
            function () {
                return $this->settings->makeView();
            },
            plugin_dir_url( __FILE__ ) . '../dist/images/moovly_small.png'
        );

        if ($this->api->auth->hasToken()) {
            add_submenu_page(
                'moovly-settings',
                __('Templates', 'moovly'),
                __('Templates', 'moovly'),
                'manage_options',
                'moovly-templates',
                function () {
                    return $this->templates->makeView();
                }
            );

            add_submenu_page(
                'moovly-settings',
                __('Projects', 'moovly'),
                __('Projects', 'moovly'),
                'manage_options',
                'moovly-projects',
                function () {
                    return $this->projects->makeView();
                }
            );
        }
    }
}
