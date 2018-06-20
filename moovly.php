<?php
/*
 * Plugin Name: Moovly
 * Description: An integration plugin for the Moovly API.
 * Author: Moovly
 * Author URI:
 * Version: 0.0.1
 * Plugin URI:
 */

require __DIR__ . '/vendor/autoload.php';

add_action('init', function () {
    (new \Moovly\Moovly())->initialize();
});
