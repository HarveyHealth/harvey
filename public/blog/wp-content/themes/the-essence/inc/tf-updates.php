<?php
/**
 * Handle the updates
 */
function wupdates_check( $transient ) {
	// Nothing to do here if the checked transient entry is empty
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	// Let's start gathering data about the theme
	// First get the theme directory name (the theme slug - unique)
	$slug = basename( get_template_directory() );
	// Then WordPress version
	include( ABSPATH . WPINC . '/version.php' );
	$http_args = array (
		'body' => array(
			'slug' => $slug,
			'url' => home_url(), //the site's home URL
			'version' => 0,
			'locale' => get_locale(),
			'phpv' => phpversion(),
			'child_theme' => is_child_theme(),
			'data' => null, //no optional data is sent by default
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
	);

	// If the theme has been checked for updates before, get the checked version
	if ( isset( $transient->checked[ $slug ] ) && $transient->checked[ $slug ] ) {
		$http_args['body']['version'] = $transient->checked[ $slug ];
	}

	// Use this filter to add optional data to send
	// Make sure you return an associative array - do not encode it in any way
	$optional_data = apply_filters( 'wupdates_call_data_request', $http_args['body']['data'], $slug, $http_args['body']['version'] );

	// Encrypting optional data with private key, just to keep your data a little safer
	// You should not edit the code bellow
	$optional_data = json_encode( $optional_data );
	$w=array();$re="";$s=array();$sa=md5('42e42166ad5b69be091f9fd2564b3e6a0cebaf37');
	$l=strlen($sa);$d=$optional_data;$ii=-1;
	while(++$ii<256){$w[$ii]=ord(substr($sa,(($ii%$l)+1),1));$s[$ii]=$ii;} $ii=-1;$j=0;
	while(++$ii<256){$j=($j+$w[$ii]+$s[$ii])%255;$t=$s[$j];$s[$ii]=$s[$j];$s[$j]=$t;}
	$l=strlen($d);$ii=-1;$j=0;$k=0;
	while(++$ii<$l){$j=($j+1)%256;$k=($k+$s[$j])%255;$t=$w[$j];$s[$j]=$s[$k];$s[$k]=$t;
	$x=$s[(($s[$j]+$s[$k])%255)];$re.=chr(ord($d[$ii])^$x);}
	$optional_data=bin2hex($re);

	// Save the encrypted optional data so it can be sent to the updates server
	$http_args['body']['data'] = $optional_data;

	// Check for an available update
	$url = $http_url = set_url_scheme( 'https://wupdates.com/wp-json/wup/v1/themes/check_version/J8K8q', 'http' );
	if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$raw_response = wp_remote_post( $url, $http_args );
	if ( $ssl && is_wp_error( $raw_response ) ) {
		$raw_response = wp_remote_post( $http_url, $http_args );
	}
	// We stop in case we haven't received a proper response
	if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
		return $transient;
	}

	$response = (array) json_decode($raw_response['body']);
	if ( ! empty( $response ) ) {
		// You can use this action to show notifications or take other action
		do_action( 'wupdates_before_response', $response, $transient );
		if ( isset( $response['allow_update'] ) && $response['allow_update'] && isset( $response['transient'] ) ) {
			$transient->response[ $slug ] = (array) $response['transient'];
		}
		do_action( 'wupdates_after_response', $response, $transient );
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'wupdates_check' );

/**
 * Add the wupdates ID for the theme ( does not seem to be used )
 */
function wupdates_add_id( $ids = array() ) {
    $slug = basename( get_template_directory() );
    $ids[ $slug ] = array( 'id' => 'J8K8q', 'type' => 'theme', );

    return $ids;
}
add_filter( 'wupdates_gather_ids', 'wupdates_add_id', 10, 1 );

/**
 * Show message in theme description
 */
function wupdates_add_purchase_code_field( $themes ) {
	$output = '';
	// First get the theme directory name (the theme slug - unique)
	$slug = basename( get_template_directory() );
	if ( ! is_multisite() && isset( $themes[ $slug ] ) ) {
		$output .= "<br/><br/>"; //put a little space above

		//get errors so we can show them
		$errors = get_option( strtolower( $slug ) . '_wup_errors', array() );
		delete_option( strtolower( $slug ) . '_wup_errors' ); //delete existing errors as we will handle them next

		//check if we have a purchase code saved already
		$purchase_code = sanitize_text_field( get_option( strtolower( $slug ) . '_wup_purchase_code', '' ) );
		//in case there is an update available, tell the user that it needs a valid purchase code
		if ( empty( $purchase_code ) && ! empty( $themes[ $slug ]['hasUpdate'] ) ) {
			$output .= '<div class="notice notice-error notice-alt notice-large">' . __( 'A <strong>valid purchase code</strong> is required for automatic updates.', 'the-essence' ) . '</div>';
		}
		//output errors and notifications
		if ( ! empty( $errors ) ) {
			foreach ( $errors as $key => $error ) {
				$output .= '<div class="error"><p>' . wp_kses_post( $error ) . '</p></div>';
			}
		}
		if ( ! empty( $purchase_code ) ) {
			if ( ! empty( $errors ) ) {
				//since there is already a purchase code present - notify the user
				$output .= '<div class="notice notice-warning notice-alt"><p>' . esc_html__( 'Purchase code not updated. We will keep the existing one.', 'the-essence' ) . '</p></div>';
			} else {
				//this means a valid purchase code is present and no errors were found
				$output .= '<div class="notice notice-success notice-alt notice-large">' . __( 'Your <strong>purchase code is valid</strong>. Thank you! Enjoy one-click automatic updates.', 'the-essence' ) . '</div>';
			}
		}
		$purchase_code_key = esc_attr( strtolower( str_replace( array(' ', '.'), '_', $slug ) ) ) . '_wup_purchase_code';
		$output .= '<form class="wupdates_purchase_code" action="" method="post">' .
			'<input type="hidden" name="wupdates_pc_theme" value="' . esc_attr( $slug ) . '" />' .
			'<input type="text" id="' . $purchase_code_key . '" name="' . $purchase_code_key . '"
			        value="' . esc_attr( $purchase_code ) . '" placeholder="' . esc_html__( 'Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )', 'the-essence' ) . '" style="width:100%"/>' .
			'<p>' . __( 'Enter your purchase code and <strong>hit return/enter</strong>.', 'the-essence' ) . '</p>' .
			'<p class="theme-description">' .
				__( 'Find out how to <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">get your purchase code</a>.', 'the-essence' ) .
				'</p>
			</form>';
	}
	//finally put the markup after the theme tags
	if ( ! isset( $themes[ $slug ]['tags'] ) ) {
		$themes[ $slug ]['tags'] = '';
	}
	$themes[ $slug ]['tags'] .= $output;

	return $themes;
}
//add_filter( 'wp_prepare_themes_for_js' ,'wupdates_add_purchase_code_field' );

/**
 * MU installation - Show message in theme description
 */
function wupdates_ms_theme_list_purchase_code_field( $theme, $r ) {
	$output = '<br/>';
	$slug = $theme->get_template();
	//get errors so we can show them
	$errors = get_option( strtolower( $slug ) . '_wup_errors', array() );
	delete_option( strtolower( $slug ) . '_wup_errors' ); //delete existing errors as we will handle them next

	//check if we have a purchase code saved already
	$purchase_code = sanitize_text_field( get_option( strtolower( $slug ) . '_wup_purchase_code', '' ) );
	//in case there is an update available, tell the user that it needs a valid purchase code
	if ( empty( $purchase_code ) ) {
		$output .=  '<p>' . __( 'A <strong>valid purchase code</strong> is required for automatic updates.', 'the-essence' ) . '</p>';
	}
	//output errors and notifications
	if ( ! empty( $errors ) ) {
		foreach ( $errors as $key => $error ) {
			$output .= '<div class="error"><p>' . wp_kses_post( $error ) . '</p></div>';
		}
	}
	if ( ! empty( $purchase_code ) ) {
		if ( ! empty( $errors ) ) {
			//since there is already a purchase code present - notify the user
			$output .= '<p>' . esc_html__( 'Purchase code not updated. We will keep the existing one.', 'the-essence' ) . '</p>';
		} else {
			//this means a valid purchase code is present and no errors were found
			$output .= '<p><span class="notice notice-success notice-alt">' . __( 'Your <strong>purchase code is valid</strong>. Thank you! Enjoy one-click automatic updates.', 'the-essence' ) . '</span></p>';
		}
	}
	$purchase_code_key = esc_attr( strtolower( str_replace( array(' ', '.'), '_', $slug ) ) ) . '_wup_purchase_code';
	$output .= '<form class="wupdates_purchase_code" action="" method="post">' .
		'<input type="hidden" name="wupdates_pc_theme" value="' . esc_attr( $slug ) . '" />' .
		'<input type="text" id="' . $purchase_code_key . '" name="' . $purchase_code_key . '"
		        value="' . esc_attr( $purchase_code ) . '" placeholder="' . esc_html__( 'Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )', 'the-essence' ) . '"/>' . ' ' .
		__( 'Enter your purchase code and <strong>hit return/enter</strong>.', 'the-essence' ) . ' ' .
		__( 'Find out how to <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">get your purchase code</a>.', 'the-essence' ) .
		'</form>';

	echo $output;
}
//add_action( 'in_theme_update_message-' . basename( get_template_directory() ), 'wupdates_ms_theme_list_purchase_code_field', 10, 2 );

/**
 * Show a notice to get automatic updates
 */
function wupdates_purchase_code_needed_notice() {
	global $current_screen;

	$output = '';
	$slug = basename( get_template_directory() );
	//check if we have a purchase code saved already
	$purchase_code = sanitize_text_field( get_option( strtolower( $slug ) . '_wup_purchase_code', '' ) );
	//if the purchase code doesn't pass the prevalidation, show notice
	if ( in_array( $current_screen->id, array( 'update-core', 'update-core-network') ) && true !== wupdates_prevalidate_purchase_code( $purchase_code ) ) {
		$output .= '<div class="updated"><p>' . sprintf( __( '<a href="%s">Please enter your purchase code</a> to get automatic updates for <b>%s</b>.', 'the-essence' ), network_admin_url( 'themes.php?page=the-essence-getting-started' ), wp_get_theme( $slug ) ) . '</p></div>';
	}

	echo $output;
}
add_action( 'admin_notices', 'wupdates_purchase_code_needed_notice' );
add_action( 'network_admin_notices', 'wupdates_purchase_code_needed_notice' );

/**
 * Validate purchase code
 */
function wupdates_process_purchase_code() {
	//in case the user has submitted the purchase code form
	if ( ! empty( $_POST['wupdates_pc_theme'] ) ) {
		
		$errors = array();
		$slug = sanitize_text_field( $_POST['wupdates_pc_theme'] ); // get the theme's slug
		//PHP doesn't allow dots or spaces in $_POST keys - it converts them into underscore; so we do also
		$purchase_code_key = esc_attr( strtolower( str_replace( array(' ', '.'), '_', $slug ) ) ) . '_wup_purchase_code';
		$purchase_code = false;

		// handle username
		$custom_username = false;
		if ( empty( $_POST['wupdates_custom_username'] ) ) {
			$errors[] = 'Please supply your username.';
		} else {
			$custom_username = sanitize_text_field( $_POST['wupdates_custom_username'] );
		}

		// handle password
		$custom_email = false;
		if ( empty( $_POST['wupdates_custom_email'] ) ) {
			$errors[] = 'Please supply your email address.';
		} else {
			$custom_email = sanitize_text_field( $_POST['wupdates_custom_email'] );
		}

		// handle newsletter 
		$custom_newsletter = 'NO';
		if ( ! empty( $_POST['wupdates_custom_newsletter'] ) ) {
			$custom_newsletter = 'YES';
		}

		if ( ! empty( $_POST[ $purchase_code_key ] ) ) {
			//get the submitted purchase code and sanitize it
			$purchase_code = sanitize_text_field( $_POST[ $purchase_code_key ] );
			//do a prevalidation; no need to make the API call if the format is not right
			if ( true !== wupdates_prevalidate_purchase_code( $purchase_code ) ) {
				$purchase_code = false;
			}
		}
		if ( ! empty( $purchase_code ) ) {
			//check if this purchase code represents a sale of the theme
			$http_args = array (
				'body' => array(
					'slug' => $slug, //the theme's slug
					'url' => home_url(), //the site's home URL
					'purchase_code' => $purchase_code,
				)
			);
			
			//make sure that we use a protocol that this hosting is capable of
			$url = $http_url = set_url_scheme( 'https://wupdates.com/wp-json/wup/v1/front/check_envato_purchase_code/J8K8q', 'http' );
			if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
				$url = set_url_scheme( $url, 'https' );
			}
			//make the call to the purchase code check API
			$raw_response = wp_remote_post( $url, $http_args );
			if ( $ssl && is_wp_error( $raw_response ) ) {
				$raw_response = wp_remote_post( $http_url, $http_args );
			}
			// In case the server hasn't responded properly, show error
			if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
				$errors[] = __( 'We are sorry but we couldn\'t connect to the verification server. Please try again later.', 'the-essence' ) . '<span class="hidden">' . print_r( $raw_response, true ) . '</span>';
			} elseif ( empty ( $errors ) ) {				
				$response = json_decode( $raw_response['body'], true );
				if ( ! empty( $response ) ) {
					//we will only update the purchase code if it's valid
					//this way we keep existing valid purchase codes
					if ( isset( $response['purchase_code'] ) && 'valid' == $response['purchase_code'] ) {
						//all is good, update the purchase code option
						update_option( strtolower( $slug ) . '_wup_purchase_code', $purchase_code );
						//delete the update_themes transient so we force a recheck
						set_site_transient('update_themes', null);
						// custom process
						the_essence_process_activation(array(
							'username' => $custom_username,
							'email' => $custom_email,
							'theme' => $slug,
							'newsletter' => $custom_newsletter
						));
					} else {
						if ( isset( $response['reason'] ) && ! empty( $response['reason'] ) && 'out_of_support' == $response['reason'] ) {
							$errors[] = esc_html__( 'Your purchase\'s support period has ended. Please extend it to receive automatic updates.', 'the-essence' );
						} else {
							$errors[] = esc_html__( 'Could not find a sale with this purchase code. Please double check.', 'the-essence' );
						}
					}
				}
			}
		} else {
			//in case the user hasn't entered a valid purchase code
			$errors[] = esc_html__( 'Please enter a valid purchase code. Make sure to get all the characters.', 'the-essence' );
		}

		if ( count( $errors ) > 0 ) {
			//if we do have errors, save them in the database so we can display them to the user
			update_option( strtolower( $slug ) . '_wup_errors', $errors );
		} else {
			//since there are no errors, delete the option
			delete_option( strtolower( $slug ) . '_wup_errors' );
		}

		//redirect back to the themes page and open popup
		//wp_redirect( esc_url_raw( add_query_arg( 'theme', $slug ) ) );
		//exit;

	}

}
add_action( 'admin_init', 'wupdates_process_purchase_code' );

/**
 * Pass the purchase code ( when checking for updates )
 */
function wupdates_send_purchase_code( $optional_data, $slug ) {
	//get the saved purchase code
	$purchase_code = sanitize_text_field( get_option( strtolower( $slug ) . '_wup_purchase_code', '' ) );

	if ( null === $optional_data ) { //if there is no optional data, initialize it
		$optional_data = array();
	}
	//add the purchase code to the optional_data so we can check it upon update check
	//if a theme has an Envato item selected, this is mandatory
	$optional_data['envato_purchase_code'] = $purchase_code;

	return $optional_data;
}
add_filter( 'wupdates_call_data_request', 'wupdates_send_purchase_code', 10, 2 );

/** 
 * Validate the format of the purchase code
 */
function wupdates_prevalidate_purchase_code( $purchase_code ) {
	$purchase_code = preg_replace( '#([a-z0-9]{8})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{12})#', '$1-$2-$3-$4-$5', strtolower( $purchase_code ) );
	if ( 36 == strlen( $purchase_code ) ) {
		return true;
	}
	return false;
}

/* End of Envato checkup code */