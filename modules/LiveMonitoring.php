<?php
namespace AnxMonitoring;

use WP_User_Query;

/**
 * Class LiveMonitoring
 *
 * @package AnxMonitoring
 */
class LiveMonitoring {

    /**
     * Simple method that check if the WordPress installation is alive and working correctly
     *
     * Checks for:
     *  - Database connection
     *  - Query for users
     *  - Registers hook for custom function
     *      usage: add_action( 'anx_monitoring_up_check', function() { **code** });
     *
     * @var $request
     *
     * @return string
     */
    public function upCheck($request) {
        global $wpdb;

        // Access check
        if (!Authorization::checkAccessToken($request)) {
            http_response_code(401);
            echo 'You are not authorized to do this';
            die();
        }

        // Check db connection
        if (!empty($wpdb->error)) {
            echo $wpdb->error->get_error_message();
            die();
        }

        // Query for users
        $user_query = new WP_User_Query(['number' => 5]);
        if (empty($user_query->results)) {
            echo "User query not working";
            die();
        }

        //Register custom hook
        do_action('anx_monitoring_up_check');

        header("Content-Type: text/plain");
        echo "OK";
        die();
    }
}
