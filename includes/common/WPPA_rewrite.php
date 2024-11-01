<?php

/**
 * Class rewrite rules the plugin.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_Rewrite extends WPPA_detail {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->setting_plugin = get_option(WPPA_PLUGIN_NAME);
	}

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $wppa_htaccess    data rewrite rulles in htaccess
	 */
	public $wppa_htaccess;


	public function set_rewrite_rules( $wp_rewrite ) {
		# Copy of the generated htaccess
		$this->wppa_htaccess = '# BEGIN WordPress<br/>' . nl2br( $wp_rewrite->mod_rewrite_rules() ) . "# END WordPress";

		return $wp_rewrite;
	}


	/**
	 * Set default rules in htaccess
	 *
	 * @return string
	 * @since    1.0.0
	 * @access   private
	 */
	public function set_default_htaccess_rules() {

		$new_rules = "";

		$new_rules .= "<IfModule mod_rewrite.c>\n";
		$new_rules .= "RewriteEngine On\n";
		$new_rules .= "RewriteBase /\n";
		$new_rules .= "RewriteRule ^index\.php$ - [L]\n\n";
		$new_rules .= "\n";
		$new_rules .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$new_rules .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$new_rules .= "RewriteRule . /index.php [L]\n";
		$new_rules .= "</IfModule>\n";

		return $new_rules;
	}


	/**
	 * Save new rules in htaccess
	 *
	 * @return string
	 * @since    1.0.0
	 * @access   private
	 */
	public function set_htaccess_rules() {
		$new_rules = "";

		$new_rules .= "<IfModule mod_rewrite.c>\n";
		$new_rules .= "RewriteEngine On\n";
		$new_rules .= "RewriteBase /\n";
		$new_rules .= "RewriteRule ^index\.php$ - [L]\n\n";


		// wp-admin
		if( !empty($this->setting_plugin['slug_wp_admin']) ) {
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_admin'] . "$ " . $this->setting_plugin['slug_wp_admin'] . " [R,L]\n";
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_admin'] . "(.*) /wp-admin/$1?%{QUERY_STRING} [QSA,L]\n";
		}

		// login
		if( !empty($this->setting_plugin['slug_wp_login']) ) {
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_login'] . "/?$ /wp-login.php [QSA,L]\n";
		}

		// lost password
		if( !empty($this->setting_plugin['slug_wp_lostpassword']) ) {
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_lostpassword'] . "/?$ /wp-login.php?action=lostpassword [QSA,L]\n";
		}

		// register url
		if( !empty($this->setting_plugin['slug_wp_register']) ) {
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_register'] . "/?$ /wp-login.php?action=register [QSA,L]\n";
		}

		// logout
		if ( !empty($this->setting_plugin['slug_wp_logout']) ) {
			$new_rules .= "RewriteRule ^" . $this->setting_plugin['slug_wp_logout'] . "/?$ /wp-login.php?action=logout [QSA,L]\n";
		}

		$new_rules .= "\n";
		$new_rules .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$new_rules .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$new_rules .= "RewriteRule . /index.php [L]\n";
		$new_rules .= "</IfModule>\n";

		return $new_rules;
	}

}