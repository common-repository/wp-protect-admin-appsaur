<?php

/**
 * The file that defines the deactivate plugin
 *
 * A class definition that includes attributes and functions deactivate plugin
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_Deactivator {

    /**
     * Deactivate plugin
     */
    public static function deactivate() {

        $rules = new WPPA_rewrite();

        add_filter( 'mod_rewrite_rules', array( $rules, 'set_default_htaccess_rules' ) );
        add_filter( 'generate_rewrite_rules', array( $rules, 'set_rewrite_rules' ) );

        flush_rewrite_rules();
    }


}