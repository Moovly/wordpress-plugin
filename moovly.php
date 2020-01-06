<?php
/*
 * Plugin Name: Moovly
 * Description: An integration plugin for the Moovly API.
 * Author: Moovly
 * Author URI: https://www.moovly.com
 * Version: {{build}}
 * Plugin URI: https://github.com/Moovly/wordpress-plugin

 */

require __DIR__ . '/vendor/autoload.php';

global $moovly;

$moovly = new \Moovly\Moovly;

add_action('init', function () use ($moovly) {
    $moovly->initialize();
});

register_activation_hook(__FILE__, 'activate_moovly_plugin');
function activate_moovly_plugin()
{
    register_uninstall_hook(__FILE__, 'remove_moovly_plugin');
}

function remove_moovly_plugin()
{
    global $moovly;

    $moovly->terminate();
}
