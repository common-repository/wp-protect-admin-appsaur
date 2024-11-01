<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin aspects of the plugin.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/view/admin
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<div class="wppa-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<div class="wppa-information">
			<div class="wppa-important">
				<?php _e('- Before save new settings create backup', $this->plugin_name);?>
			</div>
			<div class="wppa-important">
				<?php echo sprintf(
					__( '- Before save settings in this plugin You must use %1$scustom permalinks%2$s. Do that from %3$shere%4$s', $this->plugin_name ),
					'<a href="' . esc_url( plugins_url( '/assets/img/wordpress-permalink-settings_en.png', dirname(dirname(__FILE__)) ) ) . '" target="_blank">',
					'</a>',
					'<a href="' . esc_url( admin_url("options-permalink.php") ) . '">',
					'</a>'
				); ?>
			</div>
			<div class="wppa-important">
				<?php _e('- Sometimes the setting of other plug-ins can cause problems working plugin', $this->plugin_name);?>
			</div>
		</div>

		<h2 class="nav-tab-wrapper"><?php _e('Address url', $this->plugin_name);?></h2>
		<form method="POST" name="<?php echo $this->plugin_name;?>" action="options.php">

			<?php
			//Get all plugin options
			$options = get_option($this->plugin_name);

			// variable
			$slug_wp_login = (!empty($options['slug_wp_login']) ? $options['slug_wp_login'] : '');
			$slug_wp_admin = (!empty($options['slug_wp_admin']) ? $options['slug_wp_admin'] : '');
			$slug_wp_logout = (!empty($options['slug_wp_logout']) ? $options['slug_wp_logout'] : '');
			$slug_wp_lostpassword = (!empty($options['slug_wp_lostpassword']) ? $options['slug_wp_lostpassword'] : '');
			$slug_wp_register = (!empty($options['slug_wp_register']) ? $options['slug_wp_register'] : '');
			$slug_wp_login_hide = (!empty($options['slug_wp_login_hide']) ? $options['slug_wp_login_hide'] : '');
			$slug_wp_admin_hide = (!empty($options['slug_wp_admin_hide']) ? $options['slug_wp_admin_hide'] : '');

			// example url
			$slug_wp_login_url = (!empty($options['slug_wp_login']) ? site_url() . '/' . $options['slug_wp_login'] : site_url() . '/wp-login.php' );
			$slug_wp_admin_url = (!empty($options['slug_wp_admin']) ? site_url() . '/' . $options['slug_wp_admin'] : site_url() . '/wp-admin');
			$slug_wp_logout_url = (!empty($options['slug_wp_logout']) ? site_url() . '/' . $options['slug_wp_logout'] : site_url() . '/wp-login.php?action=logout' );
			$slug_wp_lostpassword_url = (!empty($options['slug_wp_lostpassword']) ? site_url() . '/' . $options['slug_wp_lostpassword'] : site_url() . '/wp-login.php?action=lostpassword' );
			$slug_wp_register_url = (!empty($options['slug_wp_register']) ? site_url() . '/' . $options['slug_wp_register'] : site_url() . '/wp-login.php?action=register' );

			$disaled = 'disabled';

			?>

			<?php
			settings_fields( $this->plugin_name );
			do_settings_sections( $this->plugin_name );
			?>

			<!-- remove some meta and generators from the <head> -->
			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->plugin_name;?>-slug_wp_login"><?php _e('Login slug', $this->plugin_name);?></label>
					<div class="wppa-item-field <?php if(isset($_SESSION['wppa_message_error']['slug_wp_login']) && !empty($_SESSION['wppa_message_error']['slug_wp_login'])) { echo 'form-invalid';} ;?>">
						<input maxlength="20" type="text" class="regular-text" id="<?php echo $this->plugin_name;?>-slug_wp_login" name="<?php echo $this->plugin_name;?>[slug_wp_login]" value="<?php if(!empty($slug_wp_login)) echo esc_attr($slug_wp_login);?>"/><br />
						<span class="margin-left-200 pl5 small-login-url"><strong>Url: </strong> <a href="<?php echo $slug_wp_login_url;?>" title=""><?php echo $slug_wp_login_url;?></a></span><br />
						<small class="margin-left-200"><?php _e('Not more than 20 characters. Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label label-slug_wp_admin" for="<?php echo $this->plugin_name;?>-slug_wp_admin"><?php _e('Wp-admin slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input maxlength="20" type="text" class="regular-text" id="<?php echo $this->plugin_name;?>-slug_wp_admin" name="<?php echo $this->plugin_name;?>[slug_wp_admin]" value="<?php if(!empty($slug_wp_admin)) echo esc_attr($slug_wp_admin);?>"/><br />
						<span class="pl5 small-wp-admin-url"><strong>Url: </strong> <a href="<?php echo $slug_wp_admin_url;?>" title="" target="_blank"><?php echo $slug_wp_admin_url;?></a></span><br />
						<small><?php _e('Not more than 20 characters. Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->plugin_name;?>-slug_wp_logout"><?php _e('Logout slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input maxlength="20" type="text" class="regular-text" id="<?php echo $this->plugin_name;?>-slug_wp_logout" name="<?php echo $this->plugin_name;?>[slug_wp_logout]" value="<?php if(!empty($slug_wp_logout)) echo esc_attr($slug_wp_logout);?>"/><br />
						<span class="margin-left-200 pl5 small-logout-url"><strong>Url: </strong> <a href="<?php echo $slug_wp_logout_url;?>" title="" target="_blank"><?php echo $slug_wp_logout_url;?></a></span><br />
						<small class="margin-left-200"><?php _e('Not more than 20 characters. Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->plugin_name;?>-slug_wp_lostpassword"><?php _e('Lost password slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input maxlength="20" type="text" class="regular-text" id="<?php echo $this->plugin_name;?>-slug_wp_lostpassword" name="<?php echo $this->plugin_name;?>[slug_wp_lostpassword]" value="<?php if(!empty($slug_wp_lostpassword)) echo esc_attr($slug_wp_lostpassword);?>"/><br />
						<span class="margin-left-200 pl5 small-lostpassword-url"><strong>Url: </strong> <a href="<?php echo $slug_wp_lostpassword_url;?>" title="" target="_blank"><?php echo $slug_wp_lostpassword_url;?></a></span><br />
						<small class="margin-left-200"><?php _e('Not more than 20 characters. Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->plugin_name;?>-slug_wp_register"><?php _e('Register slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input maxlength="20" type="text" class="regular-text" id="<?php echo $this->plugin_name;?>-slug_wp_register" name="<?php echo $this->plugin_name;?>[slug_wp_register]" value="<?php if(!empty($slug_wp_register)) echo esc_attr($slug_wp_register);?>"/><br />
						<span class="margin-left-200 pl5 small-register-url"><strong>Url: </strong> <a href="<?php echo $slug_wp_register_url;?>" title="" target="_blank"><?php echo $slug_wp_register_url;?></a></span><br />
						<small class="margin-left-200"><?php _e('Not more than 20 characters. Allowed characters are A-Z, a-z, 0-9, _ and -', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>




			<h2 class="nav-tab-wrapper"><?php _e('Hide url', $this->plugin_name);?></h2>
			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->slug_wp_login_hide;?>-slug_wp_login_hide"><?php _e('Hide wp-login slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input <?php if (empty($slug_wp_login)) { echo 'disabled'; } ?> type="checkbox" class="" id="<?php echo $this->plugin_name;?>-slug_wp_login_hide" name="<?php echo $this->plugin_name;?>[slug_wp_login_hide]" value="1" <?php if( !empty($slug_wp_login_hide) ) echo 'checked="checked" ';?> />
						<small><?php _e('Prevent access to the wp-login.php (available if you set a new login slug)', $this->plugin_name);?></small><br />
						<small><strong><?php _e('If you select to hide the wp-login.php, it will not be available registration and password reset', $this->plugin_name);?></strong></small>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="wppa-item">
					<label class="wppa-label" for="<?php echo $this->slug_wp_login_hide;?>-slug_wp_admin_hide"><?php _e('Hide wp-admin slug', $this->plugin_name);?></label>
					<div class="wppa-item-field">
						<input <?php if (empty($slug_wp_admin)) { echo 'disabled'; } ?> type="checkbox" class="" id="<?php echo $this->plugin_name;?>-slug_wp_admin_hide" name="<?php echo $this->plugin_name;?>[slug_wp_admin_hide]" value="1" <?php if( !empty($slug_wp_admin_hide) ) echo 'checked="checked" ';?> />
						<small><?php _e('Prevent access to the wp-admin (available if you set a new wp-admin slug)', $this->plugin_name);?></small>
					</div>
				</div>
			</fieldset>

			<p class="submit">
				<input type="submit" class="button-primary" id="wppa-protect-admin-save" name="<?php echo $this->plugin_name;?>-submit" value="<?php echo __('Save', $this->plugin_name)?>" />
			</p>
		</form>
	</div>
	<div class="wppa-wrapp-right">
		<a href="http://appsaur.co" title="Appsaur.co Your Team" target="_blank" >
			<img src="<?php echo plugins_url( '/assets/img/appsaur_dont_click.png', dirname(dirname(__FILE__)) ); ?>" title="Appsaur.co plugins" >
		</a>
	</div>
</div>

