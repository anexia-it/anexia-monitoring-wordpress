<?php
namespace AnxMonitoring;

use \WP_REST_Response;

/**
 * Class VersionMonitoring
 *
 * @package AnxMonitoring
 */
class VersionMonitoring {
    /**
     * Get theme related information and updates
     *
     * @return array
     */
    public static function getThemeData() {
        $themes = [];
        $usedThemes = [];

        // Check for theme updates
        do_action( "wp_update_themes" );

        // Get theme update information
        $updateThemes = get_site_transient( 'update_themes' ); // get information of updates

        // Get data for themes having updates
        foreach ($updateThemes->response as $key => $themeData) {
            // Get information from local version
            $themeInfo =  wp_get_theme($key);

            $themes[] = [
                'name' => 'theme-'.$themeData['theme'],
                'installed_version' => $themeInfo['Version'],
                'newest_version' => $themeData['new_version']
            ];

            $usedThemes[] = $themeData['theme'];
        }

        // Get data for themes having no updates
        foreach ($updateThemes->checked as $key => $version) {
            if (!in_array($key, $usedThemes)) {
                $themes[] = [
                    'name' => 'theme-'.$key,
                    'installed_version' => $version,
                    'newest_version' => $version
                ];
            }
        }

        return $themes;
    }

    /**
     * Get plugin related information and updates
     *
     * @return array
     */
    public static function getPluginData() {
        $plugins = [];

        // Check for plugin updates
        do_action( "wp_update_plugins" );

        // Get plugin update information
        $updatePlugins = get_site_transient('update_plugins');

        // Get data for plugins having updates
        foreach ($updatePlugins->response as $key => $pluginData) {
            // Get information from local version
            $pluginInfo = get_plugin_data(WP_PLUGIN_DIR . "/" . $key);

            $plugins[] = [
                'name' => 'plugin-'.$pluginData->slug,
                'installed_version' => $pluginInfo['Version'],
                'newest_version' => $pluginData->new_version
            ];
        }

        // Get data for plugins having no updates
        foreach ($updatePlugins->no_update as $key => $pluginData) {
            // Get information from local version
            $pluginInfo = get_plugin_data(WP_PLUGIN_DIR . "/" . $key);

            $plugins[] = [
                'name' => 'plugin-'.$pluginData->slug,
                'installed_version' => $pluginInfo['Version'],
                'newest_version' => $pluginData->new_version
            ];
        }

        return $plugins;
    }

    /**
     * Get module related information and updates
     *
     * @return array
     */
    public static function getModulesData() {
        return array_merge(
            self::getPluginData(),
            self::getThemeData()
        );
    }

    /**
     * Get platform related information and updates
     *
     * @return array
     */
    public static function getRuntimeData() {
        // Check for core updates
        do_action( "wp_version_check" );

        // Get core update information
        $updateCore = get_site_transient( "update_core" );

        return array(
            'platform' => 'php',
            'platform_version' => phpversion(),
            'framework' => 'wordpress',
            'framework_installed_version' => $updateCore->version_checked,
            'framework_newest_version' => (!empty($updateCore->updates)) ? $updateCore->updates[0]->version : $updateCore->version_checked
        );
    }

    /**
     * Collect all WordPress related updates
     *
     * @param $request
     * @return WP_REST_Response
     */
    public static function getUpdates($request) {
        // Perform monitor check for valid access token
        if (Authorization::checkAccessToken($request)) {
            return new WP_REST_Response(
                [
                    'runtime' => self::getRuntimeData(),
                    'modules' => self::getModulesData()
                ],
                200
            );
        }

        return Authorization::notAuthorized();
    }
}
