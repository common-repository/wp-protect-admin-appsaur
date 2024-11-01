<?php

/**
 * Class templates the plugin.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
class WPPA_Template {

	/**
	 * Load the selected admin template
	 */
	public static function LoadTemplate() {

		//Get template based on GET[page] value
		switch($_GET['page']) {
			case 'WppaHideWP':
					$template = '/view/admin/wppa-hiden-wp.php';
				break;
		}


		include_once( WPPA_WPINC_DIR . $template );
	}

}