<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/admin
 * @author     Dariusz Andryskowski
 */
class WPPA_Admin extends WPPA_Detail {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = WPPA_WPINC_VERSION;
		$this->setting_plugin = get_option($this->plugin_name);

		$this->wppa_tools = new WPPA_Tools();
		$this->rewrite = new WPPA_Rewrite();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// set file css
		wp_enqueue_style( $this->plugin_name, plugins_url( '/assets/css/wppa-admin.css', dirname(dirname(__FILE__)) ), array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// set file js
		wp_enqueue_script( $this->plugin_name, plugins_url( '/assets/js/wppa-admin.js', dirname(dirname(__FILE__)) ), array( 'jquery' ), $this->version, false );
		// add translation for the js file
		wp_localize_script( $this->plugin_name, 'wppa_hidde_wp_translate', array(
				'slug_wp_login_is_empty' => __( "Please add slug wp-login.php", $this->plugin_name ),
				'slug_wp_admin_is_empty' => __( "Please add slug wp-admin", $this->plugin_name ),
				'slug_not_allowed_char' => __( 'Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name )
			)
		);
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/**
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */

		add_options_page( __('Protect Admin - Configuration', $this->plugin_name), 'WP Protect Admin', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 *
	 */
	public function add_action_links($links, $file) {

		if ($file == WPINC_PLUGIN_DIR) {
			$settings_link = '<a href="' . admin_url('/options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once( WPPA_WPINC_DIR . '/view/admin/wppa-hiden-wp.php' );
	}

	/**
	 *  Save the plugin options
	 *
	 * @since    1.0.0
	 */
	public function options_update() {

		// save data when click submit
		if (isset ($_POST)) {
			$this->flush_changes();
		}
	}

	/**
	 * Flush the changes in htaccess
	 *
	 * @since    1.0.0
	 */
	public function flush_changes() {

		// save data in db
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate_form_change_url') );

		//Empty the cache from other plugins
		WPPA_Tools::clear_cache();

		//Build the redirects in htaccess
		$this->build_redirect();

		//Flush the changes
		global $wp_rewrite;

		$wp_rewrite->flush_rules(true);
	}

	/**
	 * Function add in .htaccess new rules. We need add the same value(wp-admin) in  /config.php
	 * create define new path for this new slug surls
	 */
	public function build_redirect() {
		add_filter( 'mod_rewrite_rules', array( $this->rewrite, 'set_htaccess_rules' ) );
		add_filter( 'generate_rewrite_rules', array( $this->rewrite, 'set_rewrite_rules' ) );

	}

	/**
	 * Validate all options fields
	 *
	 * @since    1.0.0
	 */
	public function validate_form_change_url($input) {
		// clear session error message
		unset($_SESSION['wppa_message_error']);

		// santize input
		$input = $this->filter_sanitize_text_field($input);


		// All checkboxes inputs
		$valid = array();

		$valid['slug_wp_login'] = (isset($input['slug_wp_login']) && !empty($input['slug_wp_login'])) ? str_replace(' ', '', $input['slug_wp_login']) : '';
		$valid['slug_wp_admin'] = (isset($input['slug_wp_admin']) && !empty($input['slug_wp_admin'])) ? str_replace(' ', '', $input['slug_wp_admin']) : '';
		$valid['slug_wp_logout'] = (isset($input['slug_wp_logout']) && !empty($input['slug_wp_logout'])) ? str_replace(' ', '', $input['slug_wp_logout']) : '';
		$valid['slug_wp_lostpassword'] = (isset($input['slug_wp_lostpassword']) && !empty($input['slug_wp_lostpassword'])) ? str_replace(' ', '', $input['slug_wp_lostpassword']) : '';
		$valid['slug_wp_register'] = (isset($input['slug_wp_register']) && !empty($input['slug_wp_register'])) ? str_replace(' ', '', $input['slug_wp_register']) : '';

		$valid['slug_wp_login_hide'] = (isset($input['slug_wp_login_hide']) && !empty($input['slug_wp_login_hide'])) ? $input['slug_wp_login_hide']: '';
		$valid['slug_wp_admin_hide'] = (isset($input['slug_wp_admin_hide']) && !empty($input['slug_wp_admin_hide'])) ? $input['slug_wp_admin_hide']: '';

		return $valid;
	}


	/**
	 * Santize string from array
	 * @param $array
	 * @return array|bool
	 */
	public function filter_sanitize_text_field($array) {

		$santizeArray = array();

		if (!is_array($array)) {
			return false;
		}

		foreach ($array as $key => $val ) {
			$santizeArray[$key] = sanitize_text_field($val);
		}

		return $santizeArray;
	}


	/**
	 * Function init admin
	 */
	public function init_admin_url() {

		if ( !empty($this->setting_plugin) ) {

			// check url wp-login.php
			if ($this->get_request_uri() == 'wp-login.php') {
				WPPA_Tools::page_404();
				exit;
			}

			// check url wp-admin
			if ($this->get_request_uri() == 'wp-admin' || strpos($_GET['redirect_to'], '/wp-admin/') !== false ) {

				// redirect if user is not admin
				if( ! is_admin() && (!empty(get_option('wp-protect-admin')['slug_wp_login']))  ) {
					WPPA_Tools::page_404();
					exit;
				}
			}
		}
	}

	/**
	 * Filters the login URL.
	 * @param $login_url    string      The login URL. Not HTML-encoded.
	 * @param $redirect     string      The path to redirect to on login, if supplied.
	 * @param $force_reauth     bool    Whether to force reauthorization, even if a cookie is present.
	 *
	 * @since     1.0.0
	 * @return  string     New wp-login.php with new Url
	 */
	public function replace_login_page( $login_url, $redirect='', $force_reauth='' ) {
		return str_replace("wp-login.php", $this->setting_plugin['slug_wp_login'], $login_url);
	}

	/**
	 * Replace the login URL.
	 * @param $login_url    string      The login URL. Not HTML-encoded.
	 * @param $redirect     string      The path to redirect to on login, if supplied.
	 * @param $force_reauth     bool    Whether to force reauthorization, even if a cookie is present.
	 *
	 * @since     1.0.0
	 * @return  string     New wp-login.php with new Url
	 */

	function replace_login_url( $login_url, $redirect, $force_reauth ) {
		$login_page = home_url( $this->setting_plugin['slug_wp_login'] );
		$login_url = add_query_arg( 'redirect_to', $redirect, $login_page );
		return $login_url;
	}

	/**
	 * Replace URL logi submit form.
	 *
	 * @since     1.0.0
	 * @return  string     Form with new Url login
	 */
	public function replace_login_submit_form() {

		$your_content = ob_get_contents();
		$your_content = str_replace("wp-login.php",  $this->setting_plugin['slug_wp_login'] , $your_content);
		ob_get_clean();
		echo $your_content;
	}

	/**
	 * Replace url lostpassword in form
	 */
	function replace_lostpassword_form() {
		//$slug = $array[current_filter()];
		$form = ob_get_contents();
		$form = preg_replace( "/wp-login\.php([^\"]*)/", $this->setting_plugin['slug_wp_lostpassword'] . '$1', $form);
		ob_get_clean();
		echo $form;
	}

	/**
	 * Replace url register in form
	 */
	function replace_register_form() {
		//$slug = $array[current_filter()];
		$form = ob_get_contents();
		$form = preg_replace( "/wp-login\.php([^\"]*)/", $this->setting_plugin['slug_wp_register'] . '$1', $form);
		ob_get_clean();
		echo $form;
	}

	/**
	 * Function replace redirect user when success login in wp-admin
	 *
	 * @since     1.0.0
	 * @return  string     Url new slug wp-admin or put default wp-admin if new slug is empty
	 *
	 */
	public function redirect_login_in_wp_admin () {

		//todo: gubi nowy slug wp-admin lub wystepuje blad jak jest pusty
		global $redirect_to;
		if (!isset($_GET['redirect_to'])) {
			return get_option('siteurl') ."/". (get_option('wp-protect-admin')['slug_wp_admin'] != "" ? trailingslashit( get_option('wp-protect-admin')['slug_wp_admin'] ) : "wp-admin");
		} else {
			return $redirect_to;
		}
	}

	/**
	 * Function replace redirect url lostpassword
	 *
	 * @since     1.0.0
	 * @return  string     Url slug lostpassword
	 *
	 */
	public function redirect_url_lostpassword () {
		return site_url( $this->setting_plugin['slug_wp_login'] . "?checkemail=confirm" );
	}

	/**
	 * Function replace register url
	 *
	 * @since     1.0.0
	 * @return  string     Url slug register
	 *
	 */
	public function redirect_url_registration () {
		return site_url( $this->setting_plugin['slug_wp_login'] . "?checkemail=registered" );
	}

	/**
	 * Changes wp-admin URL slug
	 *
	 * @since     1.0.0
	 * @return  string    New admin URL
	 */
	public function replace_admin_url($path) {
		return str_replace('wp-admin', ($this->setting_plugin['slug_wp_admin'] != "" ? trailingslashit( $this->setting_plugin['slug_wp_admin'] ) : "wp-admin"), $path);
	}

	/**
	 * Changes logout URL slug
	 *
	 * @since     1.0.0
	 * @return  string    Logout URL
	 */
	public function replace_logout_url($path) {
		return home_url("/". $this->setting_plugin['slug_wp_logout']);
	}

	/**
	 * Changes register URL slug
	 *
	 * @since     1.0.0
	 * @return  string    Register URL
	 */
	public function replace_register_url($path) {
		return str_replace( site_url( 'wp-login.php?action=register', 'login'), site_url($this->setting_plugin['slug_wp_register'], 'login'), $path );
	}

	/**
	 * Changes lost password URL slug
	 *
	 * @since     1.0.0
	 * @return  string    Lost password URL
	 */
	public function replace_lostpassword_url($path) {
		return str_replace( '?action=lostpassword','', str_replace(network_site_url( 'wp-login.php', 'login' ), site_url($this->setting_plugin['slug_wp_lostpassword'], 'login'), $path ) );
	}

	/**
	 * Return admin url
	 *
	 * @since     1.0.0
	 * @return  string    admin URL
	 */
	public function whats_my_admin_url() {
		$url = admin_url();
		echo '<pre><code>'; print_r( $url ); echo '</code></pre>';
	}


	/**
	 * Remove default redirect slug
	 * Documentation: https://developer.wordpress.org/reference/functions/wp_redirect_admin_locations/
	 *
	 * @since     1.0.0
	 * @return  string    admin URL
	 */
	public function remove_redirect_template_url () {
		remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
	}


	/**
	 * Add default redirect slug
	 * Documentation: https://developer.wordpress.org/reference/functions/wp_redirect_admin_locations/
	 *
	 * @since     1.0.0
	 * @return  string    admin URL
	 */
	public function add_redirect_template_url () {

		$admins = array(
			home_url( 'wp-admin', 'relative' ),
			home_url( 'dashboard', 'relative' ),
			home_url( 'admin', 'relative' ),
			site_url( 'dashboard', 'relative' ),
			site_url( 'admin', 'relative' ),
		);

		if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $admins ) ) {
			wp_redirect( admin_url() );
			exit;
		}

		$logins = array(
			home_url( $this->setting_plugin['slug_wp_login'], 'relative' )
		);
		if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $logins ) ) {
			wp_redirect( site_url( $this->setting_plugin['slug_wp_login'], 'login' ) );
			exit;
		}
	}
}