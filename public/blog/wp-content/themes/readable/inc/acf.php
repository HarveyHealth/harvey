<?php
/**
 * Filters and include calls for the ACF plugin, so it is available within the theme
 *
 * @see http://www.advancedcustomfields.com/resources/including-acf-in-a-plugin-theme/
 *
 * @package readable
 */

if ( ! READABLE_DEVELOPMENT || apply_filters( 'readable/show_acf', false ) ) {
	define( 'ACF_LITE', true );
}

// Load acf field groups from PHP file.
if ( ! READABLE_DEVELOPMENT ) {
	locate_template( 'inc/acf-field-groups.php', true, true );
}


/**
 * Fix if the ACF is not activated yet
 */
add_action( 'get_header', function () {
	if ( ! function_exists( 'get_field' ) ) {
		function get_field( $setting, $id = 0 ) {
			return false;
		}

		function have_rows( $value = false ) {
			return false;
		}
	}
} );
