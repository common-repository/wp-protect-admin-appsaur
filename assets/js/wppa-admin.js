(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-specific JavaScript source
     * should reside in this file.
     *
     * Note that this assume you're going to use jQuery, so it prepares
     * the $ function reference to be used within the scope of this
     * function.
     *
     * From here, you're able to define handlers for when the DOM is
     * ready:
     *
     * $(function() {
	 *
	 * });
     *
     * Or when the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and so on.
     *
     * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
     * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
     * be doing this, we should try to minimize doing that in our own work.
     */
    jQuery(document).ready(function (){

        jQuery('#wppa-protect-admin-save').on('click',function () {

            // clear errors
            if ( jQuery( '.wppa-item-field' ).hasClass( 'form-invalid' ) ) {
                jQuery('.wppa-item-field').removeClass('form-invalid');
            }

            if ( jQuery( 'div' ).hasClass( 'form-error' ) ) {
                jQuery('.form-error').remove();
            }

            // clear error param
            var error = false;

            var slug_wp_login =  $('#wp-protect-admin-slug_wp_login').val();
            var slug_wp_admin =  $('#wp-protect-admin-slug_wp_admin').val();
            var slug_wp_logout =  $('#wp-protect-admin-slug_wp_logout').val();
            var slug_wp_lostpassword =  $('#wp-protect-admin-slug_wp_lostpassword').val();
            var slug_wp_register =  $('#wp-protect-admin-slug_wp_register').val();
            var slug_wp_login_hide =  $('#wp-protect-admin-slug_wp_login_hide').is(':checked');
            var slug_wp_admin_hide =  $('#wp-protect-admin-slug_wp_admin_hide').is(':checked');


            // check lenght
            if ( slug_wp_login.length > 0 && valid(slug_wp_login) === false ) {
                jQuery('#wp-protect-admin-slug_wp_login').parent().addClass('form-invalid');

                jQuery("#wp-protect-admin-slug_wp_login").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_not_allowed_char + '</div>');
                error = true;
            }
            if ( slug_wp_admin.length > 0 && valid(slug_wp_admin)  === false ) {
                jQuery('#wp-protect-admin-slug_wp_admin').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_admin").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_not_allowed_char + '</div>');
                error = true;
            }

            if ( slug_wp_logout.length > 0 && valid(slug_wp_logout) === false ) {
                jQuery('#wp-protect-admin-slug_wp_logout').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_logout").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_not_allowed_char + '</div>');
                error = true;
            }

            if ( slug_wp_lostpassword.length > 0 && valid(slug_wp_lostpassword) === false ) {
                jQuery('#wp-protect-admin-slug_wp_lostpassword').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_lostpassword").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_not_allowed_char + '</div>');
                error = true;
            }

            if ( slug_wp_register.length > 0 && valid(slug_wp_register) === false ) {
                jQuery('#wp-protect-admin-slug_wp_register').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_register").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_not_allowed_char + '</div>');
                error = true;
            }



            if ( !(slug_wp_login) && slug_wp_login_hide === true ) {
                jQuery('#wp-protect-admin-slug_wp_login').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_login").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_wp_login_is_empty + '</div>');
                error = true;
            }

            if ( !(slug_wp_admin) && slug_wp_admin_hide === true ) {
                jQuery('#wp-protect-admin-slug_wp_admin').parent().addClass('form-invalid');
                jQuery("#wp-protect-admin-slug_wp_admin").parent().prepend('<div class="form-error">' + wppa_hidde_wp_translate.slug_wp_admin_is_empty + '</div>');
                error = true;
            }

            // break we found error
            if (error === true) {
                return false;
            }

            return true;

        });

        function valid(str) {

            var error = false;
            var regex = /^[A-Za-z0-9_-]+$/g;

            var m = regex.exec(str);

            if (m !== null) {

                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index !== regex.lastIndex) {
                    regex.lastIndex++;
                    error = true;
                }
            }

            return error;
        }
    })
})( jQuery );

