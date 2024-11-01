<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$textdomain    = WPPA_PLUGIN_NAME;
		$locale        = apply_filters( 'plugin_locale', get_locale(), $textdomain );
		$lang_dir      = dirname( dirname( dirname( __FILE__ ) ) ) . '/languages/';
		$mofile        = sprintf( '%s.mo', $textdomain . '-' .$locale );
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WPPA_LANG_DIR . '/plugins/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			load_textdomain( $textdomain, $mofile_global );
		} else {
			load_textdomain( $textdomain, $mofile_local );
		}
	}

}
