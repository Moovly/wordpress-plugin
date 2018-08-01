<?php
/*
 * Plugin Name: Moovly
 * Description: An integration plugin for the Moovly API.
 * Author: Moovly
 * Author URI: https://www.moovly.com
 * Version: 1.0.{{build}}
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
    if (version_compare(PHP_VERSION, '7.1', '<')) {
        wp_die(
            '<p>The <strong>Moovly</strong> plugin requires PHP version 7.1 or greater. ' 
                . 'To read more about this and other prerequisites, head over to our '
                . '<a href="https://developer.moovly.com/docs/integrations/wordpress">Documentation</a> page</p>'
            ,
            'Plugin Activation Error',
            ['response' => 200, 'back_link' => true]
        );
    }
    
    register_uninstall_hook(__FILE__, 'remove_moovly_plugin');
}

function remove_moovly_plugin()
{
    global $moovly;

    $moovly->terminate();
}
