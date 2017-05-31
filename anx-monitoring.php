<?php
/*
Plugin Name: Anexia Monitoring
Plugin URI:
Description: A WordPress plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.
Version: 1.0
Author: Anexia
Author URI: http://www.anexia-it.com
Text Domain: anexia-monitoring
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
include_once('modules/authorization.php');
include_once('modules/version-monitoring.php');
include_once('modules/live-monitoring.php');

/**
 * CORS Headers
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

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
        array(
            'methods' => 'GET',
            'callback' => 'AnxMonitoring_VersionMonitoring::getUpdates',
        )
    );

    /**
     * Custom endpoint to check if the website is working correctly
     *
     * URL: /wp-json/anxapi/v1/up/
     */
    register_rest_route(
        $namespace, '/up',
        array(
            'methods' => 'GET',
            'callback' => 'AnxMonitoring_LiveMonitoring::upCheck',
        )
    );
});
