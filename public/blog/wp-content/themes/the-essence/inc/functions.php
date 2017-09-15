<?php

/**
 * Table Of Contents
 *
 * The_Essence_Aq_Resize ( Image resizing class )
 * the_essence_aq_resize ( Image resizing function )
 * the_essence_get_social_count ( Returns amount of social shares a page has )
 * the_essence_get_theme_mod ( Returns customizer option value )
 * the_essence_get_post_meta ( Returns post meta value )
 * the_essence_get_attachment_alt ( Returns the alt attribute for an attachment )
 * the_essence_get_instagram_images ( Returns images from instagram account )
 */

if( ! class_exists('The_Essence_Aq_Resize') ) {

	/**
	 * Image resizing class
	 *
	 * @since 1.0
	 */
	class The_Essence_Aq_Resize {

		/**
		 * The singleton instance
		 */
		static private $instance = null;

		/**
		 * No initialization allowed
		 */
		private function __construct() {}

		/**
		 * No cloning allowed
		 */
		private function __clone() {}

		/**
		 * For your custom default usage you may want to initialize an Aq_Resize object by yourself and then have own defaults
		 */
		static public function getInstance() {
			if(self::$instance == null) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Run, forest.
		 */
		public function process( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = true ) {

			// Validate inputs.
			if ( ! $url || ( ! $width && ! $height ) ) return false;

			$upscale = true;

			// Caipt'n, ready to hook.
			if ( true === $upscale ) add_filter( 'image_resize_dimensions', array($this, 'aq_upscale'), 10, 6 );

			// Define upload path & dir.
			$upload_info = wp_upload_dir();
			$upload_dir = $upload_info['basedir'];
			$upload_url = $upload_info['baseurl'];
			
			$http_prefix = "http://";
			$https_prefix = "https://";
			
			/* if the $url scheme differs from $upload_url scheme, make them match 
			   if the schemes differe, images don't show up. */
			if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
				$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
			}
			elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
				$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);      
			}
			

			// Check if $img_url is local.
			if ( false === strpos( $url, $upload_url ) ) return false;

			// Define path of image.
			$rel_path = str_replace( $upload_url, '', $url );
			$img_path = $upload_dir . $rel_path;

			// Check if img path exists, and is an image indeed.
			if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

			// Get image info.
			$info = pathinfo( $img_path );
			$ext = $info['extension'];
			list( $orig_w, $orig_h ) = getimagesize( $img_path );

			// Get image size after cropping.
			$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
			$dst_w = $dims[4];
			$dst_h = $dims[5];

			// Return the original image only if it exactly fits the needed measures.
			if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
				$img_url = $url;
				$dst_w = $orig_w;
				$dst_h = $orig_h;
			} else {
				// Use this to check if cropped image already exists, so we can return that instead.
				$suffix = "{$dst_w}x{$dst_h}";
				$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
				$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

				if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
					// Can't resize, so return false saying that the action to do could not be processed as planned.
					return $url;
				}
				// Else check if cache exists.
				elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
					$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
				}
				// Else, we resize the image and return the new resized image url.
				else {

					$editor = wp_get_image_editor( $img_path );

					if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
						return $url;

					$resized_file = $editor->save();

					if ( ! is_wp_error( $resized_file ) ) {
						$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
						$img_url = $upload_url . $resized_rel_path;
					} else {
						return $url;
					}

				}
			}

			// Okay, leave the ship.
			if ( true === $upscale ) remove_filter( 'image_resize_dimensions', array( $this, 'aq_upscale' ) );

			// Return the output.
			if ( $single ) {
				// str return.
				$image = $img_url;
			} else {
				// array return.
				$image = array (
					0 => $img_url,
					1 => $dst_w,
					2 => $dst_h
				);
			}

			return $image;
		}

		/**
		 * Callback to overwrite WP computing of thumbnail measures
		 */
		function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
			if ( ! $crop ) return null; // Let the wordpress default function handle this.

			// Here is the point we allow to use larger image size than the original one.
			$aspect_ratio = $orig_w / $orig_h;
			$new_w = $dest_w;
			$new_h = $dest_h;

			if ( ! $new_w ) {
				$new_w = intval( $new_h * $aspect_ratio );
			}

			if ( ! $new_h ) {
				$new_h = intval( $new_w / $aspect_ratio );
			}

			$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

			$crop_w = round( $new_w / $size_ratio );
			$crop_h = round( $new_h / $size_ratio );

			$s_x = floor( ( $orig_w - $crop_w ) / 2 );
			$s_y = floor( ( $orig_h - $crop_h ) / 2 );

			return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		}

	}
	
}


if ( ! function_exists('the_essence_aq_resize') ) {

	/**
	 * Resize an image using The_Essence_Aq_Resize Class
	 *
	 * @since 1.0
	 *
	 * @param string $url     The URL of the image
	 * @param int    $width   The new width of the image
	 * @param int    $height  The new height of the image
	 * @param bool   $crop    To crop or not to crop, the question is now
	 * @param bool   $single  If true only returns the URL, if false returns array
	 * @param bool   $upscale If image not big enough for new size should it upscale
	 * @return mixed If $single is true return new image URL, if it is false return array
	 *               Array contains 0 = URL, 1 = width, 2 = height
	 */
	function the_essence_aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

		 if( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {

			$args = array(
				'resize' => "$width,$height"
			);
			if ( $single == true ) {
				return jetpack_photon_url( $url, $args );
			} else {
				$image = array (
					0 => $img_url,
					1 => $width,
					2 => $height
				);
				return jetpack_photon_url( $url, $args );
			}

		} else {

			$aq_resize = The_Essence_Aq_Resize::getInstance();
			return $aq_resize->process( $url, $width, $height, $crop, $single, $upscale );
			
		}

	}

}

/**
 * Returns amount of social shares a page has
 *
 * @since 1.0
 *
 * @param int     $post_ID ID of the post/page. Default false, uses get_the_ID()
 * @param int     $refresh_in Amount of seconds for cached info to be stored. Default 3600.
 * @return array  Array containing amount of shares. Keys are fb, twitter and pinterest. 
 */
function the_essence_get_social_count( $post_ID = false, $refresh_in = 3600 ) {

	// If ID nt supplied use current
	if ( $post_ID == false ) {
		$post_ID = get_the_ID();
	}	

	// Transient
	$transient_id = 'the_essence_social_shares_count_v4_' . $post_ID;

	if ( false === ( $share_info = get_transient( $transient_id ) ) ) {

		$the_url = get_permalink( $post_ID );

		// Defaults
		$share_info = array(
			'fb' => 0,
			'twitter' => 0,
			'pinterest' => 0,
			'googleplus'=> 0,
			'total' => 0,
		);

		// Facebook
		$fb_get = wp_remote_get( 'http://graph.facebook.com/?id=' . $the_url );
		$fb_count = 0;
		if ( is_array( $fb_get ) ) {
			$fb_get_body = json_decode( $fb_get['body'] );
			if ( isset( $fb_get_body->share ) ) {
				$fb_count = $fb_get_body->share->share_count;
			} else {
				$fb_count = 0;
			}
		}
		$fb_count = apply_filters( 'the_essence_social_count_fb', $fb_count );
		$share_info['fb'] = $fb_count;

		// Twitter									
		$twitter_get = wp_remote_get( 'http://opensharecount.com/count.json?url=' . $the_url );
		$twitter_count = 0;
		if ( is_array( $twitter_get ) ) {
			$twitter_get_body = json_decode( $twitter_get['body'] );
			if ( isset( $twitter_get_body->count ) ) {
				$twitter_count = $twitter_get_body->count;
			} else {
				$twitter_count = 0;
			}
			$share_info['twitter'] = $twitter_count;
		}

		// Pinterest 
		$pinterest_get = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?url=' . $the_url );
		$pinterest_count = 0;
		if ( is_array( $pinterest_get ) ) {
			$pinterest_get_body = json_decode( preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest_get['body'] ) );
			if ( isset( $pinterest_get_body->count ) ) {
				$pinterest_count = $pinterest_get_body->count;
			} else {
				$pinterest_count = 0;
			}
			$share_info['pinterest'] = $pinterest_count;
		}

		// Google +
		$googleplus_count = 0;
		$curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ" );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $the_url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
        $curl_results = curl_exec( $curl );
        curl_close( $curl );
        $json = json_decode( $curl_results, true );        
        $share_info['googleplus'] = intval( $json[0]['result']['metadata']['globalCounts']['count'] );		
        $googleplus_count = $share_info['googleplus'];

		// Total amount
		$share_info['total'] = $fb_count + $twitter_count + $pinterest_count + $googleplus_count;

		// Check if there is data
		if ( isset( $share_info ) ) {
			set_transient( $transient_id, $share_info, $refresh_in );											
		} else {
			$share_info = false;
		}

	}

	// Pass the data back
	$share_info = apply_filters( 'the_essence_social_count', $share_info );

	// Fix total amount ( K if over 1000 )
	if ( $share_info['total'] >= 1000 ) {
		$share_info['total'] = round( $share_info['total'] / 1000, 1 ) . 'K';
	}

	return $share_info;

}

/**
 * Returns customizer option value
 *
 * @since 1.0
 */
function the_essence_get_theme_mod( $option_id, $default = '' ) {

	$return = get_theme_mod( THE_ESSENCE_CUSTOMIZER_PREPEND . $option_id, $default );
	if ( $return == '' ) { $return = $default; }

	return $return;

}

/**
 * Returns post meta value
 *
 * @since 1.0
 */
function the_essence_get_post_meta( $post_id, $option_id ) {

	$option_id = '_the_essence_' . $option_id;

	return get_post_meta( $post_id, $option_id , true );

}

/**
 * Returns the ALT attribute for an attachment
 *
 * @since 1.0
 *
 * @param string   $attachment_ID The ID of the attachment
 * @return string  The ALT attribute text
 */
function the_essence_get_attachment_alt( $attachment_ID ) {

	// Get ALT
	$thumb_alt = trim( strip_tags( get_post_meta( $attachment_ID, '_wp_attachment_image_alt', true) ) );
	
	// No ALT supplied get attachment info
	if ( empty( $thumb_alt ) )
		$attachment = get_post( $attachment_ID );
	
	// Use caption if no ALT supplied
	if ( empty( $thumb_alt ) )
		$thumb_alt = trim(strip_tags( $attachment->post_excerpt ));
	
	// Use title if no caption supplied either
	if ( empty( $thumb_alt ) )
		$thumb_alt = trim(strip_tags( $attachment->post_title ));

	// Return ALT
	return esc_attr( $thumb_alt );

}

/**
 * Returns images from instagra account
 *
 * @since 1.0
 */
function the_essence_get_instagram_images( $username = '17395902', $amount = 20 ) {

	$access_token = the_essence_get_theme_mod( 'footer_instagram_access_token', false );
	if ( ! $access_token ) {
		return false;
	}

	$transient_id = 'the_essence_instagram_transient_' . $username;

	if ( false === ( $images = get_transient( $transient_id ) ) ) {

		$args = array(
			'timeout'     => 30,
		);

		// Get Images
		$url = 'https://api.instagram.com/v1/users/' . $username . '/media/recent/?access_token=' . $access_token . '&count=' . $amount;
		$data = json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $args ) ), true );

		// Check if images are returned
		if ( isset( $data['data'] ) ) {
			
			$images_data = $data['data'];
			$images = array();

			// Generate array
			foreach ( $images_data as $image ) {

				$images[] = array(
					'image' => $image['images']['thumbnail']['url'],
					'url' => $image['link'],
				);

			}

			// Set Trainsient
			set_transient( $transient_id, $images, 12 * HOUR_IN_SECONDS );

		} else {
			$images = false;
		}

	}

	return $images;

}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper',     10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

function the_essence_woocommerce_support() {
    add_theme_support( 'woocommerce' );
} add_action( 'after_setup_theme', 'the_essence_woocommerce_support' );

function the_essence_woocommerce_before() {

	echo '<div class="wrapper clearfix">';
		echo '<div id="content" class="col col-8">';

}add_action( 'woocommerce_before_main_content', 'the_essence_woocommerce_before', 1 );

function the_essence_woocommerce_after() {

	echo '</div>';

}add_action( 'woocommerce_after_main_content', 'the_essence_woocommerce_after', 99999 );

function the_essence_woocommerce_after_sidebar() {

	echo '</div>';

}add_action( 'woocommerce_sidebar', 'the_essence_woocommerce_after_sidebar', 999999 );

function the_essence_mega_menu( $themes ) {

    $themes['essence_1468874603'] = array(
        'title' => 'The Essence',
        'container_background_from' => 'rgba(0, 0, 0, 0)',
        'container_background_to' => 'rgba(0, 0, 0, 0)',
        'menu_item_align' => 'center',
        'menu_item_background_hover_from' => 'rgba(0, 0, 0, 0)',
        'menu_item_background_hover_to' => 'rgba(0, 0, 0, 0)',
        'menu_item_spacing' => '23px',
        'menu_item_link_font_size' => '17px',
        'menu_item_link_height' => '50px',
        'menu_item_link_color' => 'rgb(35, 35, 35)',
        'menu_item_link_weight' => 'bold',
        'menu_item_link_color_hover' => 'rgb(35, 35, 35)',
        'menu_item_link_weight_hover' => 'bold',
        'menu_item_link_padding_left' => '0px',
        'menu_item_link_padding_right' => '0px',
        'panel_background_from' => 'rgb(255, 255, 255)',
        'panel_background_to' => 'rgb(255, 255, 255)',
        'panel_border_color' => 'rgb(218, 218, 218)',
        'panel_border_left' => '1px',
        'panel_border_right' => '1px',
        'panel_border_top' => '1px',
        'panel_border_bottom' => '1px',
        'panel_header_color' => 'rgb(17, 17, 17)',
        'panel_header_text_transform' => 'none',
        'panel_header_font_size' => '14px',
        'panel_header_padding_bottom' => '15px',
        'panel_header_margin_bottom' => '15px',
        'panel_header_border_color' => 'rgb(218, 218, 218)',
        'panel_header_border_bottom' => '1px',
        'panel_widget_padding_left' => '20px',
        'panel_widget_padding_right' => '20px',
        'panel_widget_padding_top' => '20px',
        'panel_widget_padding_bottom' => '20px',
        'panel_font_size' => '14px',
        'panel_font_color' => 'rgb(35, 35, 35)',
        'panel_font_family' => 'inherit',
        'panel_second_level_font_color' => 'rgb(35, 35, 35)',
        'panel_second_level_font_color_hover' => 'rgb(35, 35, 35)',
        'panel_second_level_text_transform' => 'uppercase',
        'panel_second_level_font' => 'inherit',
        'panel_second_level_font_size' => '14px',
        'panel_second_level_font_weight' => 'bold',
        'panel_second_level_font_weight_hover' => 'bold',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_border_color' => '#555',
        'panel_third_level_font_color' => 'rgb(56, 56, 56)',
        'panel_third_level_font_color_hover' => 'rgb(56, 56, 56)',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '14px',
        'flyout_width' => '200px',
        'flyout_menu_background_from' => 'rgb(255, 255, 255)',
        'flyout_menu_background_to' => 'rgb(255, 255, 255)',
        'flyout_border_color' => 'rgb(218, 218, 218)',
        'flyout_border_left' => '1px',
        'flyout_border_right' => '1px',
        'flyout_border_top' => '1px',
        'flyout_border_bottom' => '1px',
        'flyout_menu_item_divider' => 'on',
        'flyout_menu_item_divider_color' => 'rgb(222, 222, 222)',
        'flyout_link_padding_left' => '15px',
        'flyout_link_padding_right' => '15px',
        'flyout_link_height' => '40px',
        'flyout_background_from' => 'rgba(0, 0, 0, 0)',
        'flyout_background_to' => 'rgba(0, 0, 0, 0)',
        'flyout_background_hover_from' => 'rgba(0, 0, 0, 0)',
        'flyout_background_hover_to' => 'rgba(0, 0, 0, 0)',
        'flyout_link_size' => '14px',
        'flyout_link_color' => 'rgb(35, 35, 35)',
        'flyout_link_color_hover' => 'rgb(35, 35, 35)',
        'flyout_link_family' => 'inherit',
        'responsive_breakpoint' => '768px',
        'line_height' => '50px',
        'mobile_columns' => '1',
        'toggle_background_from' => 'rgba(0, 0, 0, 0)',
        'toggle_background_to' => 'rgba(0, 0, 0, 0)',
        'toggle_font_color' => 'rgb(35, 35, 35)',
        'mobile_background_from' => 'rgb(255, 255, 255)',
        'mobile_background_to' => 'rgb(255, 255, 255)',
        'custom_css' => '/** Push menu onto new line **/
#{$wrap} {
    clear: both;
}',
    );
    return $themes;

} add_filter( 'megamenu_themes', 'the_essence_mega_menu' );