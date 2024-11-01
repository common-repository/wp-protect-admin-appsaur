<?php

/**
 * The file that defines the core tools plugin classes
 *
 * A class definition that includes attributes and functions used in
 * public side and the admin side.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_Tools {

	/**
	 * Add replacement withint the list
	 *
	 * @param mixed $old_url - old url before replace
	 * @param mixed $new_url - new url before replace
	 */
	function add_replacement($old_url, $new_url = '') {

		if ( !empty( $new_url ) ) {
			$urls_replacement[ $old_url ] = $new_url;
		} else {
			$urls_replacement[ $old_url ] = $new_url;
		}

		return $urls_replacement[ $old_url ];
	}

	/**
	 * Fumction clear cache in plugins
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public static function clear_cache() {
		if (function_exists('w3tc_pgcache_flush')) {
			w3tc_pgcache_flush();
		}
		if (function_exists('wp_cache_clear_cache')) {
			wp_cache_clear_cache();
		}
	}

	/**
	 * Redirect on 404 page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function page_404() {
		$wp_query = new WP_Query();

		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}

	/**
	 * Redirect to rewrited login url instead of wp-login.php
	 * @param string $path
	 * @return string
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function redirect_logout() {
		$url = site_url() . '/wp-login.php?loggedout=true';

		wp_redirect($url);

	}

	/**
	 * Function get path from url eg. wp-admin; wp-login.php
	 * @return mixed
	 */
	public static function request_uri() {
		$part = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$part = trim($part, "/");
		$part = strtolower($part);
		$part = explode("/", $part);
		return $part[0];
	}

}