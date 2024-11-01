<?php

/**
 * The file that defines the activate plugin
 *
 * A class definition that includes attributes and functions activate plugin
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_Activator {

    /**
     * Activate plugin
     */
    public static function activate() {

        $rules = new WPPA_rewrite();

        add_filter( 'mod_rewrite_rules', array( $rules, 'set_htaccess_rules' ) );
        add_filter( 'generate_rewrite_rules', array( $rules, 'set_rewrite_rules' ) );

        flush_rewrite_rules();
    }
}