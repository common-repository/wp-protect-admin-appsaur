<?php
/*
 * Plugin Name: WP Protect Admin
 * Plugin URI:
 * Description: Protect Your Admin Page in wordpress
 * Version:     1.0.0
 * Author:      Appsaur.co, Dariusz Andryskowski
 * Author URI:  http://www.appsaur.co/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-protect-admin
 * Domain Path: /wp-protect-admin
 *
 * WP Admin Protect is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Admin Protect is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Admin Protect. If not, see {URI to Plugin License}.
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!defined('WPPA_WPINC_VERSION')) {
	define('WPPA_WPINC_VERSION', '1.0.0');
}

if (!defined('WPPA_PLUGIN_NAME')) {
	define('WPPA_PLUGIN_NAME', 'wp-protect-admin');
}

if (!defined('WPPA_WPINC_DIR')) {
	define('WPPA_WPINC_DIR', dirname(__FILE__) );
}

if (!defined('WPPA_LANG_DIR')) {
	define('WPPA_LANG_DIR', WPPA_WPINC_DIR );
}

if (!defined('WPINC_PLUGIN_DIR')) {
	define('WPINC_PLUGIN_DIR', plugin_basename(__FILE__));
}


/* include class */
require_once plugin_dir_path(__FILE__) . 'includes/common/abstract/WPPA_detail.php';
require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_tools.php';

/**
 * Init hide address url
 */
function wppa_init() {

	$array_plugin_setting = get_option(WPPA_PLUGIN_NAME);

	if( !empty($array_plugin_setting['slug_wp_login']) && !empty($array_plugin_setting['slug_wp_login_hide']) ) {
		// hide wp-login.php
		if(WPPA_Tools::request_uri() == 'wp-login.php') {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . site_url() . "/404");
			exit;
		}
	}

	if( !empty($array_plugin_setting['slug_wp_admin']) && !empty($array_plugin_setting['slug_wp_admin_hide']) ) {
		// hide wp-admin
		if(WPPA_Tools::request_uri() == 'wp-admin' ) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . site_url() . "/404");
			exit;
		}
	}

	if( !empty($array_plugin_setting['slug_wp_logout']) && WPPA_Tools::request_uri() == $array_plugin_setting['slug_wp_logout']) {
		// logout
		setcookie( is_ssl() ? SECURE_AUTH_COOKIE : AUTH_COOKIE, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH . $array_plugin_setting['slug_wp_admin'], COOKIE_DOMAIN );
		wp_logout();
		wp_redirect(get_option('siteurl'));
		exit;

	}

	//register
	if ( get_option( 'users_can_register' ) == 0 && !empty($array_plugin_setting['slug_wp_register'])  ) {
		if ( WPPA_Tools::request_uri() == $array_plugin_setting['slug_wp_register'] && $array_plugin_setting['slug_wp_admin_hide'] == 1 ) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . site_url() . "/404");
			exit;
		}
	}


}

add_action('init', 'wppa_init');


/**
 * The code that runs during plugin activation.
 * This action is documented in classes/WPPA_activator.php
 */
function wppa_activate() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_rewrite.php';
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_activator.php';
	WPPA_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in class/WPPA_deactivator.php
 */
function wppa_deactivate() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_tools.php';
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_rewrite.php';
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_deactivator.php';
	WPPA_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in class/WPPA_deactivator.php
 */
function wppa_uninstall() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_rewrite.php';
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPPA_uninstallator.php';
	WPPA_Uninstallator::uninstall();
}

register_activation_hook(__FILE__, 'wppa_activate');
register_deactivation_hook(__FILE__, 'wppa_deactivate');
register_uninstall_hook(__FILE__, 'wppa_uninstall');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/common/WPPA.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wppa_run() {
	if (is_admin) {
		$plugin = new WPPA();
		$plugin->run();
	}
}

wppa_run();


