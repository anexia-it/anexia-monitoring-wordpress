=== Anexia Monitoring ===
Contributors: anxhnezbeda
License: MIT

A WordPress plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.

== Description ==
A WordPress plugin used to monitor updates for core, plugins and themes. It can be also used to check if the website
is alive and working correctly.

The plugin registers some custom REST endpoints which can be used for monitoring. Make sure that the
**ANX_MONITORING_ACCESS_TOKEN** is defined, since this is used for authorization. The endpoints will return a 401
HTTP_STATUS code if the token is not defined or invalid, and a 200.

= Version monitoring of core, plugins and themes =

Returns all a list with platform and module information.

**Active permalinks**

	/wp-json/anxapi/v1/modules/?access_token=custom_access_token

**Default**

	/?rest_route=/anxapi/v1/modules/&access_token=custom_access_token

= Live monitoring =

This endpoint can be used to verify if the application is alive and working correctly. It checks if the database
connection is working and makes a query for users. It allows to register custom check by using hooks.

**Active permalinks**

	/wp-json/anxapi/v1/up/?access_token=custom_access_token

**Default**

	/?rest_route=/anxapi/v1/up/&access_token=custom_access_token

== Installation ==
In the projects wp-config.php add the access token configuration:

	define(\'ANX_MONITORING_ACCESS_TOKEN\', \'custom_access_token\');
