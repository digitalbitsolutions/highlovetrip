<?php
/**
 * Local Docker config for this project.
 *
 * Uses environment variables from `.env` and keeps production credentials
 * out of version control.
 */

function hlt_getenv( $name, $default = '' ) {
	$value = getenv( $name );

	return false === $value ? $default : $value;
}

define( 'DB_NAME', hlt_getenv( 'WORDPRESS_DB_NAME', 'wordpress' ) );
define( 'DB_USER', hlt_getenv( 'WORDPRESS_DB_USER', 'wpuser' ) );
define( 'DB_PASSWORD', hlt_getenv( 'WORDPRESS_DB_PASSWORD', 'change-me' ) );
define( 'DB_HOST', hlt_getenv( 'WORDPRESS_DB_HOST', 'db:3306' ) );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY', hlt_getenv( 'WORDPRESS_AUTH_KEY', 'local-auth-key' ) );
define( 'SECURE_AUTH_KEY', hlt_getenv( 'WORDPRESS_SECURE_AUTH_KEY', 'local-secure-auth-key' ) );
define( 'LOGGED_IN_KEY', hlt_getenv( 'WORDPRESS_LOGGED_IN_KEY', 'local-logged-in-key' ) );
define( 'NONCE_KEY', hlt_getenv( 'WORDPRESS_NONCE_KEY', 'local-nonce-key' ) );
define( 'AUTH_SALT', hlt_getenv( 'WORDPRESS_AUTH_SALT', 'local-auth-salt' ) );
define( 'SECURE_AUTH_SALT', hlt_getenv( 'WORDPRESS_SECURE_AUTH_SALT', 'local-secure-auth-salt' ) );
define( 'LOGGED_IN_SALT', hlt_getenv( 'WORDPRESS_LOGGED_IN_SALT', 'local-logged-in-salt' ) );
define( 'NONCE_SALT', hlt_getenv( 'WORDPRESS_NONCE_SALT', 'local-nonce-salt' ) );

$table_prefix = '8DnIsWt_';

define( 'WP_ALLOW_MULTISITE', true );
define( 'WP_HOME', hlt_getenv( 'WORDPRESS_HOME_URL', 'http://localhost:8088' ) );
define( 'WP_SITEURL', hlt_getenv( 'WORDPRESS_SITE_URL', 'http://localhost:8088' ) );
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'DISABLE_WP_CRON', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );

if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'DISALLOW_FILE_EDIT', true );
define( 'CONCATENATE_SCRIPTS', false );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
