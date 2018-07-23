<?php

namespace Moovly;

use Moovly\Api\Api;
use Moovly\Projects;
use Moovly\Settings;
use Moovly\PostVideos;
use Moovly\Actions\Actions;
use Moovly\Shortcodes\Shortcodes;

class Moovly
{
    public $version;

    public $settings;

    public $api;

    public $shortcodes;

    public $postVideos;

    public $templates;

    public $projects;

    public $actions;

    public function __construct()
    {
        $this->shortcodes = new Shortcodes;
        $this->postVideos = new PostVideos;
        $this->templates = new Templates;
        $this->projects = new Projects;
        $this->settings = new Settings;
        $this->actions = new Actions;
        $this->api = new Api;
    }

    public function initialize()
    {
        $this->api->register();
        $this->shortcodes->register();
        $this->actions->register();

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
        if (strpos($page, 'moovly') === false) {
            return;
        }


        add_filter('admin_body_class', function ($classes) {
            return "$classes moovly-plugin";
        });

        wp_enqueue_style('moovly', plugins_url("moovly/dist/moovly.css"), [], $this->version, 'all');
        wp_register_script('moovly', plugins_url("moovly/dist/moovly-plugin.js"), [], $this->version, true);
        wp_localize_script('moovly', 'moovlyApiSettings', [
            'root' => esc_url_raw(rest_url($this->api->domain)),
            'version' => $this->api->version,
            'nonce' => wp_create_nonce('wp_rest'),
        ]);

        wp_enqueue_script('moovly');
        wp_add_inline_script('moovly', $this->getAssetsScript(), $after = false);
    }

    public function registerAssets()
    {
        wp_enqueue_style('moovly', plugins_url("moovly/dist/moovly.css"), $this->version, 'all');
        wp_register_script('moovly', plugins_url("moovly/dist/moovly.js"), [], $this->version, true);
        wp_localize_script('moovly', 'moovlyApiSettings', [
            'root' => esc_url_raw(rest_url($this->api->domain)),
            'version' => $this->api->version,
            'nonce' => wp_create_nonce('wp_rest'),
        ]);

        wp_enqueue_script('moovly');
    }

    public function getAssetsScript()
    {
        $logo = plugin_dir_url(__DIR__) . "/dist/images/moovly.png";
        return "
        window.moovlyAssets = {
            logo: '{$logo}',
        };
        ";
    }

    public function addMenuItems()
    {
        add_menu_page(
            __('Moovly', 'moovly'),
            __('Moovly', 'moovly'),
            'manage_options',
            'moovly',
            function () {
                return $this->makeView();
            },
            plugin_dir_url(__DIR__) . '/dist/images/moovly_small.png'
        );

        add_submenu_page(
            'moovly',
            __('Settings', 'moovly'),
            __('Settings', 'moovly'),
            'manage_options',
            'moovly-settings',
            function () {
                return $this->settings->makeView();
            }
        );

        if ($this->api->auth->hasToken()) {
            add_submenu_page(
                'moovly',
                __('Templates', 'moovly'),
                __('Templates', 'moovly'),
                'manage_options',
                'moovly-templates',
                function () {
                    return $this->templates->makeView();
                }
            );

            add_submenu_page(
                'moovly',
                __('Projects', 'moovly'),
                __('Projects', 'moovly'),
                'manage_options',
                'moovly-projects',
                function () {
                    return $this->projects->makeView();
                }
            );

            add_submenu_page(
                'moovly',
                __('Post Videos', 'moovly'),
                __('Post Videos', 'moovly'),
                'manage_options',
                'moovly-post-videos',
                function () {
                    return $this->postVideos->makeView();
                }
            );
        }
    }

    public function makeView()
    {
        echo "<moovly></moovly>";
    }
}
