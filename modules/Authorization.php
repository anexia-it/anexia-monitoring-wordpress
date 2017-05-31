<?php
namespace AnxMonitoring;

use WP_REST_Response;

/**
 * Class Authorization
 *
 * @package AnxMonitoring
 */
class Authorization {

    /**
     * Simple token based authorization check
     *
     * @param $request
     * @return bool
     */
    public static function checkAccessToken($request) {
        $params = $request->get_params();

        // Access token must be in GET params
        if (!isset($params['access_token'])) {
            return false;
        }

        // Access token must be configured
        if (!defined('ANX_MONITORING_ACCESS_TOKEN')) {
            return false;
        }

        // Check if access token is correct
        if ($params['access_token'] !== ANX_MONITORING_ACCESS_TOKEN) {
            return false;
        }

        return true;
    }

    /**
     * Returns a not authorized REST response
     *
     * @return WP_REST_Response
     */
    public static function notAuthorized() {
        return new WP_REST_Response(
            [
                'code' => 'rest_unauthorized',
                'message' => 'You are not authorized to do this',
                'data' => [
                    'status' => 401
                ]
            ],
            401
        );
    }
}
