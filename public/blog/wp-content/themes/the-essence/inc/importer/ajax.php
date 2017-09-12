<?php

/**
 * Close
 */

function mt_importer_ajax_close_installer() {

	// Allowed to do this?
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		$response = array();
		$response['status'] = 'success';

		update_option( 'mt_ajax_installer', 'closed' );

		// Encode response
		$response_json = json_encode( $response );	

		// AJAX phone home
		header( "Content-Type: application/json" );
		echo $response_json;

		// Asta la vista
		exit;

	}
			
} add_action( 'wp_ajax_mt-ajax-close-installer', 'mt_importer_ajax_close_installer' );

/**
 * Home Pages
 */

function mt_importer_ajax_install_nav_menus() {

	// Allowed to do this?
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		$response = array();
		$response['status'] = 'success';

		// get locations
		$locations = get_theme_mod('nav_menu_locations');

		/**
		 * Primary
		 */

		// Check if the menu exists
		$menu_exists = wp_get_nav_menu_object( 'Primary' );

		// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( 'Primary' );		
			$locations['primary'] = $menu_id;
		}

		/**
		 * Top Bar
		 */

		// Check if the menu exists
		$menu_exists = wp_get_nav_menu_object( 'Top Bar' );

		// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( 'Top Bar' );		
			$locations['top-bar'] = $menu_id;
		}

		/**
		 * Side Panel
		 */

		// Check if the menu exists
		$menu_exists = wp_get_nav_menu_object( 'Side Panel' );

		// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( 'Side Panel' );		
			$locations['panel'] = $menu_id;

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Panel Item #1',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Panel Item #2',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Panel Item #3',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));
		}

		/**
		 * Footer
		 */

		// Check if the menu exists
		$menu_exists = wp_get_nav_menu_object( 'Footer' );

		// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( 'Footer' );		
			$locations['footer'] = $menu_id;

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Footer Item #1',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Footer Item #2',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Footer Item #3',
				'menu-item-url' => '#',
				'menu-item-status' => 'publish',
			));

		}

		// Set new locations
		set_theme_mod( 'nav_menu_locations', $locations );

		// Encode response
		$response_json = json_encode( $response );	

		// AJAX phone home
		header( "Content-Type: application/json" );
		echo $response_json;

		// Asta la vista
		exit;

	}
			
} add_action( 'wp_ajax_mt-ajax-install-nav-menus', 'mt_importer_ajax_install_nav_menus' );

/**
 * Home Pages
 */

function mt_importer_ajax_install_home_page() {

	// Allowed to do this?
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		$response = array();
		$response['status'] = 'success';

		$datas = array(
			array(
				'post_title' => 'Home',
				'meta' => array(
					'_the_essence_homepage_style' => '1_cs_12',
				),
			)
		);

		$count = 0;

		foreach ( $datas as $data ) {
			
			$count++;

			// Create post object
			$the_post = array(
				'post_title' => $data['post_title'],
				'post_status' => 'publish',
				'post_type' => 'page',
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $the_post );

			// If post added
			if ( $post_id ) {

				// set homepage template
				update_post_meta( $post_id, '_wp_page_template', 'template-home.php' );

				// Set as front page
				if ( $count == 1 ) {
					update_option( 'page_on_front', $post_id );
					update_option( 'show_on_front', 'page' );
				}

				// if meta is set
				if ( isset( $data['meta'] ) ) {
					foreach ( $data['meta'] as $meta_key => $meta_value ) {
						update_post_meta( $post_id, $meta_key, $meta_value );
					}
				}

				// add to menu
				$menu = wp_get_nav_menu_object( 'Primary' );
				$menu = $menu->term_id;
				$menu_parent = wp_update_nav_menu_item( $menu, 0, array(
					'menu-item-title' => 'Home',
					'menu-item-object' => 'page',
					'menu-item-object-id' => $post_id,
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => 0,
				));

				// add to menu
				$menu = wp_get_nav_menu_object( 'Top Bar' );
				$menu = $menu->term_id;
				$menu_parent = wp_update_nav_menu_item( $menu, 0, array(
					'menu-item-title' => 'Home',
					'menu-item-object' => 'page',
					'menu-item-object-id' => $post_id,
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => 0,
				));

			} else {
				$response['status'] = 'fail';
			}

		}

		// Encode response
		$response_json = json_encode( $response );	

		// AJAX phone home
		header( "Content-Type: application/json" );
		echo $response_json;

		// Asta la vista
		exit;

	}
			
} add_action( 'wp_ajax_mt-ajax-install-home-page', 'mt_importer_ajax_install_home_page' );

/**
 * Contact page
 */

function mt_importer_ajax_install_contact_page() {

	// Allowed to do this?
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		$response = array();
		$response['status'] = 'success';

		$post_excerpt = '';
		$post_content = 'Suspendisse in mi lorem. Quisque purus lacus, dictum eget tincidunt nec, pulvinar eu augue. Curabitur feugiat egestas scelerisque. Integer scelerisque placerat ligula. Integer laoreet arcu sit amet molestie luctus. Integer accumsan ligula velit, nec commodo mauris scelerisque a.

		Insert contact form shortcode here';

		$url = get_template_directory_uri() . '/inc/importer/images/placeholder.png';
		$tmp = download_url( $url );
		$post_id = 0;
		$desc = 'Placeholder';
		$file_array = array();

		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = 'placeholder.png';
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );

		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
		
		for ( $i=1; $i <= 1; $i++ ) { 
			
			$date = $i;
			if ( $i < 10 ) {
				$date = '0' . $i;
			}

			// Create post object
			$the_post = array(
				'post_title' => 'Contact',
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_content' => $post_content,
				'post_excerpt' => $post_excerpt,
				'post_date' => date( '2016-04-01 ' . $date . ':00:00' )
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $the_post );

			if ( $post_id && $id ) {
				add_post_meta($post_id, '_thumbnail_id', $id, true);
				update_post_meta( $post_id, '_wp_page_template', 'template-page-sidebar.php' );
			}

			// Add to menu
			$menu = wp_get_nav_menu_object( 'Primary' );
			$menu = $menu->term_id;
			wp_update_nav_menu_item( $menu, 0, array(
				'menu-item-title' => 'Contact',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $post_id,
				'menu-item-type' => 'post_type',
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => 0,
			));

			// Add to menu
			$menu = wp_get_nav_menu_object( 'Top Bar' );
			$menu = $menu->term_id;
			wp_update_nav_menu_item( $menu, 0, array(
				'menu-item-title' => 'Contact',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $post_id,
				'menu-item-type' => 'post_type',
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => 0,
			));

		}

		// Encode response
		$response_json = json_encode( $response );	

		// AJAX phone home
		header( "Content-Type: application/json" );
		echo $response_json;

		// Asta la vista
		exit;

	}
			
} add_action( 'wp_ajax_mt-ajax-install-contact-page', 'mt_importer_ajax_install_contact_page' );

/**
 * Blog Posts
 */

function mt_importer_ajax_install_blog_posts() {

	// Allowed to do this?
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		$response = array();
		$response['status'] = 'success';

		$post_excerpt = 'Nam ultrices orci nibh, eget malesuada nibh eleifend sit amet. Aliquam laoreet diam ut quam fringilla egestas. Cras commodo erat quis congue pretium. Aliquam erat volutpat. Quisque elementum tortor elit, ac porta leo ultrices id. Aliquam sit amet mattis mi. Donec facilisis consectetur interdum. Curabitur aliquam consequat ullamcorper. Maecenas eget lectus commodo, laoreet nulla eu, viverra enim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.';
		$post_content = 'Mauris vehicula efficitur mi, vel sollicitudin lectus vulputate a. Phasellus vulputate nunc libero, eu faucibus sem bibendum in. Aenean mollis quis diam sed cursus. Integer tristique rhoncus sapien vitae semper. Mauris euismod venenatis sem vitae congue.

Duis ullamcorper diam eget porttitor sagittis. Mauris porttitor magna in interdum vestibulum. Integer nec cursus neque. Mauris eu nibh rhoncus, laoreet sapien id, tincidunt turpis. Etiam mattis dapibus laoreet. Vestibulum bibendum tortor vel felis commodo ultrices. In in elit vitae eros suscipit commodo ut tristique erat. Vestibulum vehicula turpis id quam euismod vulputate.

Quisque lacinia, purus non porta malesuada, lectus tortor iaculis odio, nec laoreet massa dui sit amet elit. Sed tempus bibendum nisi eget vehicula. Maecenas quis leo eu augue faucibus aliquam.

Quisque sed pharetra odio, eu consectetur dui. Etiam scelerisque sagittis nunc, a scelerisque lorem. Fusce commodo tempus diam sed hendrerit. In ullamcorper odio eu pretium consectetur.

Proin quis nunc ut quam fermentum dignissim. Fusce mi nisl, auctor non laoreet a, auctor vel sem. Ut quis ex quis turpis accumsan molestie. Cras lobortis, elit vitae tincidunt varius, arcu augue vehicula tellus, vel aliquet ante odio eu mi. Nam nec nulla elit.

Quisque lacinia, purus non porta malesuada, lectus tortor iaculis odio, nec laoreet massa dui sit amet elit.';

		$url = get_template_directory_uri() . '/inc/importer/images/placeholder.png';
		$tmp = download_url( $url );
		$post_id = 0;
		$desc = 'Placeholder';
		$file_array = array();

		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = 'placeholder.png';
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );

		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
		
		for ( $i=1; $i <= 10; $i++ ) { 
			
			$date = $i;
			if ( $i < 10 ) {
				$date = '0' . $i;
			}

			// Create post object
			$the_post = array(
				'post_title' => 'Example Blog Post #' . $i,
				'post_status' => 'publish',
				'post_type' => 'post',
				'post_content' => $post_content,
				'post_excerpt' => $post_excerpt,
				'post_date' => date( '2016-04-01 ' . $date . ':00:00' )
			);

			// Insert the post into the database
			$post_id = wp_insert_post( $the_post );

			if ( $post_id && $id ) {
				add_post_meta($post_id, '_thumbnail_id', $id, true);
			}

			if ( $i < 6 ) {
				wp_set_post_terms( $post_id, 'featured', 'post_tag' );
			}			

		}

		// Encode response
		$response_json = json_encode( $response );	

		// AJAX phone home
		header( "Content-Type: application/json" );
		echo $response_json;

		// Asta la vista
		exit;

	}
			
} add_action( 'wp_ajax_mt-ajax-install-blog-posts', 'mt_importer_ajax_install_blog_posts' );