# Anexia Monitoring

A WordPress plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.

## Installation and configuration

Install the plugin by copying all files into the plugins directory.

In the projects wp-config.php add the access token configuration:
```
define('ANX_MONITORING_ACCESS_TOKEN', 'custom_access_token');
```

## Usage

The plugin registers some custom REST endpoints which can be used for monitoring. Make sure that the
**ANX_MONITORING_ACCESS_TOKEN** is defined, since this is used for authorization. The endpoints will return a 401
HTTP_STATUS code if the token is not defined or invalid, and a 200.

#### Version monitoring of core, plugins and themes

Returns all a list with platform and module information.

**URL:**
* Active permalinks: `/wp-json/anxapi/v1/modules/?access_token=custom_access_token`
* Default: `/?rest_route=/anxapi/v1/modules/&access_token=custom_access_token`

Response headers:
```
Status Code: 200 OK
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: application/json
```

Response body:
```
{
   "runtime":{
      "platform":"php",
      "platform_version":"7.0.19",
      "framework":"WordPress",
      "framework_installed_version":"4.5.8",
      "framework_newest_version":"4.7.5"
   },
   "modules":[
      {
         "name":"plugin-akismet",
         "installed_version":"3.1.10",
         "newest_version":"3.3.2"
      },
      {
         "name":"plugin-content-anchor-links",
         "installed_version":"1.4",
         "newest_version":"1.4"
      },
      {
         "name":"plugin-hello-dolly",
         "installed_version":"1.6",
         "newest_version":"1.6"
      },
      {
         "name":"theme-twentyfifteen",
         "installed_version":"1.5",
         "newest_version":"1.7"
      },
      {
         "name":"theme-twentysixteen",
         "installed_version":"1.2",
         "newest_version":"1.3"
      },
      {
         "name":"theme-twentyfourteen",
         "installed_version":"1.9",
         "newest_version":"1.9"
      }
   ]
}
```


#### Live monitoring

This endpoint can be used to verify if the application is alive and working correctly. It checks if the database
connection is working and makes a query for users. It allows to register custom check by using hooks.

**URL:**
* Active permalinks: `/wp-json/anxapi/v1/up/?access_token=custom_access_token`
* Default: `/?rest_route=/anxapi/v1/up/&access_token=custom_access_token`

Response headers:
```
Status Code: 200 OK
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true
Allow: GET
Content-Type: text/plain
```

Response body:
```
OK
```
##### Custom live monitoring hooks

This check can be defined into the themes functions.php or in the plugins.
```
add_action( 'anx_monitoring_up_check', function() {
    ...
    My custom is alive check
    ...
});
```

## List of developers

* Harald Nezbeda, Lead developer

## Project related external resources

* [WordPresss documentation](https://developer.wordpress.org/reference/)
