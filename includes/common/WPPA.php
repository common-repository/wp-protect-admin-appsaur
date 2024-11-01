<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA extends WPPA_Detail {

    /**
     * Include class files from classes path
     * @param string $dir m - ain dir
     * @param string $path path file
     * @param string $class included file name without .php
     */
    public static function classInclude($dir, $path, $class) {

        if (file_exists($dir . $path . $class . '.php')) {
            require_once $dir . $path . $class . '.php';
        }
    }

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

        $this->set_plugin_name(WPPA_PLUGIN_NAME);
        $this->version = WPPA_WPINC_VERSION;
        $this->site_url = trailingslashit( site_url() );
        $this->setting_plugin = get_option($this->plugin_name);

        $this->load_dependencies();
        $this->set_init();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }


    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WPPA_Loader. Orchestrates the hooks of the plugin.
     * - WPPA_i18n. Defines internationalization functionality.
     * - WPPA_Admin. Defines all hooks for the admin area.
     * - WPPA_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class tools WPPA
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPPA_tools');
        /**
         * The class responsible for load templates
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPPA_loader');

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPPA_template');

        /**
         * The class responsible for defining rewrite rules
         * of the plugin.
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPPA_rewrite');

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPPA_i18n');

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/admin/', 'WPPA_admin');

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        WPPA::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/public/', 'WPPA_public');
    }


    /**
     * Get default data and set default configuration
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_init() {
        $this->loader = new WPPA_Loader();

        $this->obj_wppa_admin = new WPPA_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->obj_wppa_admin->init_admin_url();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WPPA_i18n class in order to set the domain and to register the hook
     * with WordPress.     * @since    1.0.0
     * @access   private private
     */
    private function set_locale() {

        $plugin_i18n = new WPPA_I18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $this->loader->add_action( 'admin_enqueue_scripts', $this->obj_wppa_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $this->obj_wppa_admin, 'enqueue_scripts' );

        // Save/Update our plugin options
        $this->loader->add_action( 'admin_init', $this->obj_wppa_admin, 'options_update');

        // Changes wp-login slug
        if ( !empty( get_option('wp-protect-admin')['slug_wp_login']) ) {
            // replace login url
            $this->loader->add_filter('login_url', $this->obj_wppa_admin, 'replace_login_url', 999, 3);

            // change slug login form
            $this->loader->add_action( 'login_form', $this->obj_wppa_admin, 'replace_login_submit_form', 999,1);
        }

        // Changes wp-admin URL slug
        if ( !empty( get_option('wp-protect-admin')['slug_wp_admin']) ) {
            // change slug when user login in wp-admin
            $this->loader->add_filter( 'login_redirect', $this->obj_wppa_admin, 'redirect_login_in_wp_admin', 1 );

            // rewrite slug admin_url
            $this->loader->add_filter( 'admin_url', $this->obj_wppa_admin, 'replace_admin_url', 1);
        }

        //  Changes logout URL slug
        if ( !empty( $this->setting_plugin['slug_wp_logout']) ) {
            $this->loader->add_filter( 'logout_url', $this->obj_wppa_admin, 'replace_logout_url', 10, 2 );
        }

        //  Changes register URL slug
        if ( !empty( $this->setting_plugin['slug_wp_register']) ) {
            // Changes registration URL slug everywhere
            $this->loader->add_filter( 'register', $this->obj_wppa_admin, 'replace_register_url', 10, 2 );
        }
        //  Changes lostpoassword URL slug
        if ( !empty( $this->setting_plugin['slug_wp_register']) ) {
            // Changes lostpassword URL slug everywhere
            $this->loader->add_filter( 'register', $this->obj_wppa_admin, 'replace_register_url', 10, 1 );

            // change slug register form
            $this->loader->add_action( 'register_form', $this->obj_wppa_admin, 'replace_register_form', 10, 2 );

            // redirection after submitting register form
            $this->loader->add_filter( 'registration_redirect', $this->obj_wppa_admin, 'redirect_url_registration', 10, 3 );
        }

        // lost password
        if ( !empty($this->setting_plugin['slug_wp_lostpassword']) ) {
            // Changes lostpassword URL slug everywhere
            $this->loader->add_filter( 'lostpassword_url', $this->obj_wppa_admin, 'replace_lostpassword_url', 10, 1 );

            // change slug lostpassword form
            $this->loader->add_action( 'lostpassword_form', $this->obj_wppa_admin, 'replace_lostpassword_form', 10, 2 );

            // redirection after submitting lost password form
            $this->loader->add_filter( 'lostpassword_redirect', $this->obj_wppa_admin, 'redirect_url_lostpassword', 10, 3 );
        }

        // Add menu item
        $this->loader->add_action( 'admin_menu', $this->obj_wppa_admin, 'add_plugin_admin_menu' );

        // Add Settings link to the
        $this->loader->add_filter('plugin_action_links', $this->obj_wppa_admin, 'add_action_links', 10, 2);

        $this->loader->add_filter('set_auth_cookie', $this->obj_wppa_admin, 'set_admin_cookie', 999, 2);

        // remove redirect old address
        $this->loader->add_action( 'template_redirect', $this->obj_wppa_admin, 'remove_redirect_template_url');


    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new WPPA_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
}