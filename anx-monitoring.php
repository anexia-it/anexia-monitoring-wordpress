<?php
/*
Plugin Name: Anexia Monitoring
Plugin URI: https://github.com/anx-hnezbeda/anexia-monitoring-wordpress
Description: A WordPress plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.
Version: 1.1.1
Author: Anexia
Author URI: http://www.anexia-it.com
Text Domain: anx-monitoring
License: MIT
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

/**
 * Load plugin class files
 */
include_once('modules/Authorization.php');
include_once('modules/VersionMonitoring.php');
include_once('modules/LiveMonitoring.php');

/**
 * Register custom REST API endpoints
 */
add_action( 'rest_api_init', function () {
    $namespace = 'anxapi/v1';

    /**
     * Custom endpoint for runtime and modules information
     *
     * URL: /wp-json/anxapi/v1/modules/
     */
    register_rest_route(
        $namespace, '/modules',
        [
            'methods' => 'GET',
            'callback' => [
                new AnxMonitoring\VersionMonitoring(),
                'getUpdates',
            ]
        ]
    );

    /**
     * Custom endpoint to check if the website is working correctly
     *
     * URL: /wp-json/anxapi/v1/up/
     */
    register_rest_route(
        $namespace, '/up',
        [
            'methods' => 'GET',
            'callback' => [
                new AnxMonitoring\LiveMonitoring(),
                'upCheck',
            ],
        ]
    );
});
