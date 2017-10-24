<?php

/**
 * Register the options
 */
function the_essence_customizer_register_options( $options ) {

	$prefix = THE_ESSENCE_CUSTOMIZER_PREPEND;

	// General
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_general',
		'title' => __( '- General', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'title' => __( 'Logo Type', 'the-essence' ),
			'id'	=> $prefix . 'logo_type',
			'def'	=> 'image',
			'opts'  => array(
				'image' => __( 'Image', 'the-essence' ),
				'text' => __( 'Textual', 'the-essence' ),
			)
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => __( 'Logo', 'the-essence' ),
			'id'	=> $prefix . 'logo',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => __( 'Logo - Retina', 'the-essence' ),
			'id'	=> $prefix . 'logo_retina',
			'def'	=> '',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_general', $options );

	// Social
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_blog_post_listing',
		'title' => __( '- Blog Post Listing', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_multi_checkbox',
			'opts'  => array(
				'author' => 'Author',
				'comments' => 'Comments',
				'shares_count' => 'Shares Count',
				'shares_actions' => 'Shares Actions',
			),
			'title' => esc_attr__( 'Meta Elements', 'the-essence' ),
			'id'	=> $prefix . 'blog_listing_meta_elements',
			'def'   => array( 'author', 'comments', 'shares_count', 'shares_actions' ),
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Capitalize first letter', 'the-essence' ),
			'id'	=> $prefix . 'capitalize_letter',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'timeago' => 'Time Ago',
				'date' => 'Date',
			),
			'title' => esc_attr__( 'Date or Time Ago', 'the-essence' ),
			'id'	=> $prefix . 'date_format',
			'def'	=> 'timeago',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_blog_post_listing', $options );

	// blog post single
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_blog_post_single',
		'title' => __( '- Blog Post Single', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'full_content' => esc_html__( 'Full Content', 'the-essence' ),
				'content_sidebar' => esc_html__( 'Content + Sidebar', 'the-essence' ),
			),
			'title' => esc_attr__( 'Layout', 'the-essence' ),
			'id'	=> $prefix . 'blog_single_layout',
			'def'	=> 'content_sidebar',
		);

		$options[] = array(
			'type'	=> 'option_multi_checkbox',
			'opts'  => array(
				'author' => 'Author',
				'category' => 'Category',
				'comments' => 'Comments',
				'shares' => 'Shares',
			),
			'title' => esc_attr__( 'Meta Elements', 'the-essence' ),
			'id'	=> $prefix . 'blog_single_meta_elements',
			'def'   => array( 'author', 'category', 'comments', 'shares' ),
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Related Posts Section', 'the-essence' ),
			'id'	=> $prefix . 'blog_single_related_posts_section',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'About Author Section', 'the-essence' ),
			'id'	=> $prefix . 'blog_single_about_author_section',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'prevnext' => 'Previous and Next',
				'titles' => 'Post Titles',
			),
			'title' => esc_attr__( 'Prev/Next labels', 'the-essence' ),
			'id'	=> $prefix . 'blog_single_prev_next_labels',
			'def'	=> 'prevnext',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_blog_post_single', $options );

	// Header
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_header',
		'title' => __( '- Header', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Top Bar', 'the-essence' ),
			'id'	=> $prefix . 'header_top_bar',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Small Carousel - Homepage', 'the-essence' ),
			'id'	=> $prefix . 'header_small_carousel_home',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Small Carousel - Inner Pages', 'the-essence' ),
			'id'	=> $prefix . 'header_small_carousel_pages',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Big Carousel', 'the-essence' ),
			'id'	=> $prefix . 'header_big_carousel',
			'def'	=> 'enabled',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_header', $options );

	// promo boxes
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_promo_boxes',
		'title' => __( '- Home Promo Boxes', 'the-essence' ),
	);		

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Enable/Disable', 'the-essence' ),
			'id'	=> $prefix . 'promo_boxes',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 1 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_1_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 1 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_1_title',
			'def'	=> 'About Myself',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 1 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_1_subtitle',
			'def'	=> 'Learn More',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 1 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_1_bg_image',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 2 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_2_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 2 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_2_title',
			'def'	=> 'On Instagram',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 2 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_2_subtitle',
			'def'	=> 'Follow Me',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 2 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_2_bg_image',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 3 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_3_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 3 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_3_title',
			'def'	=> 'My Travels',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 3 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_3_subtitle',
			'def'	=> 'Discover',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 3 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_3_bg_image',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 4 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_4_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 4 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_4_title',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 4 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_4_subtitle',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 4 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_4_bg_image',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 5 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_5_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 5 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_5_title',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 5 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_5_subtitle',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 5 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_5_bg_image',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 6 - URL', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_6_url',
			'def'	=> '',			
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 6 - Title', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_6_title',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Box 6 - Subtitle', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_6_subtitle',
			'def'	=> 'Learn More',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => esc_attr__( 'Box 6 - Image', 'the-essence' ),
			'id'	=> $prefix . 'promo_box_6_bg_image',
			'def'	=> '',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_promo_boxes', $options );

	// footer
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_footer',
		'title' => __( '- Footer', 'the-essence' ),
	);		

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Posts', 'the-essence' ),
			'id'	=> $prefix . 'footer_posts',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Posts - Amount', 'the-essence' ),
			'id'	=> $prefix . 'footer_posts_amount',
			'def'	=> '12',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Posts - Category', 'the-essence' ),
			'id'	=> $prefix . 'footer_posts_category',
			'def'	=> '12',
			'descr' => 'Show posts of a specific category. Enter the category ID here. For multiple categories separate with comma (,)',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'comment_count' => 'Comment count',
				'title' => 'Title ( alphabetic )',
				'date' => 'Date',
				'rand' => 'Random',
			),
			'title' => esc_attr__( 'Posts - Order By', 'the-essence' ),
			'id'	=> $prefix . 'footer_posts_order_by',
			'def'	=> 'comment_count',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'DESC' => 'Descending',
				'ASC' => 'Ascending',
			),
			'title' => esc_attr__( 'Posts - Order', 'the-essence' ),
			'id'	=> $prefix . 'footer_posts_order',
			'def'	=> 'DESC',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Widgets', 'the-essence' ),
			'id'	=> $prefix . 'footer_widgets',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Instagram', 'the-essence' ),
			'id'	=> $prefix . 'footer_instagram',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Instagram - Access Token', 'the-essence' ),
			'descr' => 'You can get an access token at <a target="_blank" href="http://instagram.pixelunion.net">http://instagram.pixelunion.net/</a>',
			'id'	=> $prefix . 'footer_instagram_access_token',
			'def'	=> '',
		);		

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Instagram - User ID', 'the-essence' ),
			'id'	=> $prefix . 'footer_instagram_user_id',
			'def'	=> '',
		);		

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Copyright Text', 'the-essence' ),
			'id'	=> $prefix . 'footer_copy_text',
			'def'	=> 'Designed &amp; Developed by <a href="http://meridianthemes.net/" rel="nofollow">MeridianThemes</a>',
		);		

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_footer', $options );

	// Social
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_social',
		'title' => __( '- Social', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Twitter URL', 'the-essence' ),
			'id'	=> $prefix . 'social_twitter',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Facebook URL', 'the-essence' ),
			'id'	=> $prefix . 'social_facebook',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Youtube URL', 'the-essence' ),
			'id'	=> $prefix . 'social_youtube',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Vimeo URL', 'the-essence' ),
			'id'	=> $prefix . 'social_vimeo',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Tumblr URL', 'the-essence' ),
			'id'	=> $prefix . 'social_tumblr',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Pinterest URL', 'the-essence' ),
			'id'	=> $prefix . 'social_pinterest',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'LinkedIn URL', 'the-essence' ),
			'id'	=> $prefix . 'social_linkedin',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Instagram URL', 'the-essence' ),
			'id'	=> $prefix . 'social_instagram',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Github URL', 'the-essence' ),
			'id'	=> $prefix . 'social_github',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Google Plus URL', 'the-essence' ),
			'id'	=> $prefix . 'social_googleplus',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Dribbble URL', 'the-essence' ),
			'id'	=> $prefix . 'social_dribbble',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Dropbox URL', 'the-essence' ),
			'id'	=> $prefix . 'social_dropbox',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Flickr URL', 'the-essence' ),
			'id'	=> $prefix . 'social_flickr',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Foursquare URL', 'the-essence' ),
			'id'	=> $prefix . 'social_foursquare',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Behance URL', 'the-essence' ),
			'id'	=> $prefix . 'social_behance',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'Vine URL', 'the-essence' ),
			'id'	=> $prefix . 'social_vine',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => esc_attr__( 'RSS URL', 'the-essence' ),
			'id'	=> $prefix . 'social_rss',
			'def'	=> '',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_social', $options );

	// side panel
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_side_panel',
		'title' => __( '- Side Panel', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Side Panel', 'the-essence' ),
			'id'	=> $prefix . 'side_panel_state',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_image',
			'title' => __( 'Logo', 'the-essence' ),
			'id'	=> $prefix . 'logo_panel',
			'def'	=> '',
		);		

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_side_panel', $options );

	// Other
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'the_essence_other',
		'title' => __( '- Other', 'the-essence' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'1_cs_12' => 'Style A / Content + Sidebar / One Column',
				'1_fc_12' => 'Style A / Full Content / One Column',
				'1_fc_6' => 'Style A / Full Content / Two Columns',
				'3_cs_6' => 'Style B / Content + Sidebar / Two Columns',
				'3_cs_4' => 'Style B / Content + Sidebar / Three Columns',
				'3_fc_6' => 'Style B / Full Content / Two Columns',
				'3_fc_4' => 'Style B / Full Content / Three Columns',
				'3_fc_3' => 'Style B / Full Content / Four Columns',
				'4_cs_6' => 'Style C / Content + Sidebar / Two Columns',
				'4_cs_4' => 'Style C / Content + Sidebar / Three Columns',
				'4_fc_6' => 'Style C / Full Content / Two Columns',
				'4_fc_4' => 'Style C / Full Content / Three Columns',
				'4_fc_3' => 'Style C / Full Content / Four Columns',
				'7_cs_6' => 'Style D / Content + Sidebar / Two Columns',
				'7_fc_6' => 'Style D / Full Content / Two Columns',
				'7_fc_4' => 'Style D / Full Content / Three Columns',
				'7_fc_3' => 'Style D / Full Content / Four Columns',
			),
			'title' => esc_attr__( 'Archive Style', 'the-essence' ),
			'id'	=> $prefix . 'archive_style',
			'def'	=> '1_cs_12',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'1_cs_12' => 'Style A / Content + Sidebar / One Column',
				'1_fc_12' => 'Style A / Full Content / One Column',
				'1_fc_6' => 'Style A / Full Content / Two Columns',
				'3_cs_6' => 'Style B / Content + Sidebar / Two Columns',
				'3_cs_4' => 'Style B / Content + Sidebar / Three Columns',
				'3_fc_6' => 'Style B / Full Content / Two Columns',
				'3_fc_4' => 'Style B / Full Content / Three Columns',
				'3_fc_3' => 'Style B / Full Content / Four Columns',
				'4_cs_6' => 'Style C / Content + Sidebar / Two Columns',
				'4_cs_4' => 'Style C / Content + Sidebar / Three Columns',
				'4_fc_6' => 'Style C / Full Content / Two Columns',
				'4_fc_4' => 'Style C / Full Content / Three Columns',
				'4_fc_3' => 'Style C / Full Content / Four Columns',
				'7_cs_6' => 'Style D / Content + Sidebar / Two Columns',
				'7_fc_6' => 'Style D / Full Content / Two Columns',
				'7_fc_4' => 'Style D / Full Content / Three Columns',
				'7_fc_3' => 'Style D / Full Content / Four Columns',
			),
			'title' => esc_attr__( 'Search Style', 'the-essence' ),
			'id'	=> $prefix . 'search_style',
			'def'	=> '1_cs_12',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => 'Enabled',
				'disabled' => 'Disabled',
			),
			'title' => esc_attr__( 'Sticky Navigation', 'the-essence' ),
			'id'	=> $prefix . 'navigation_sticky_state',
			'def'	=> 'enabled',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'loadmore' => esc_html__( 'Load More ( AJAX )', 'the-essence' ),
				'loadmore_auto' => esc_html__( 'Automatic Load More ( AJAX )', 'the-essence' ),
				'numbered' => esc_html__( 'Classic Numbered', 'the-essence' ),
				'prevnext' => esc_html__( 'Previous/Next Buttons', 'the-essence' ),
			),
			'title' => esc_html__( 'Pagination Type', 'the-essence' ),
			'id'	=> $prefix . 'pagination_type',
			'def'	=> 'loadmore',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'number' => esc_html__( 'Classic Numbered', 'the-essence' ),
				'next' => esc_html__( 'Previous/Next Buttons', 'the-essence' ),
			),
			'title' => esc_html__( 'Post Content Pagination Type', 'the-essence' ),
			'id'	=> $prefix . 'post_pagination_type',
			'def'	=> 'number',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'enabled' => esc_html__( 'Enabled', 'the-essence' ),
				'disabled' => esc_html__( 'Disabled', 'the-essence' ),
			),
			'title' => esc_html__( 'Show only 1 category in post listings', 'the-essence' ),
			'id'	=> $prefix . 'show_one_cat_in_listings',
			'def'	=> 'disabled',
		);

		// allows devs to add options
		$options = apply_filters( 'the_essence_options_other', $options );

	return $options;

} add_filter( 'the_essence_customizer_register', 'the_essence_customizer_register_options', 10, 1 );

/**
 * Add options to customizer
 */
function the_essence_customizer_register( $wp_customize ) {
	
	$customizer_options = apply_filters( 'the_essence_customizer_register', array() );

	$section_priority = 200;
	$setting_priority = 5;
	$current_section_id = '';
	$current_setting_id = '';
	
	foreach ( $customizer_options as $customizer_option ) {

		if( $customizer_option['type'] == 'section' ){
			
			/* New Section */
			
			$section_priority += 50;
			$setting_priority = 5;
			$current_section_id = $customizer_option['id'];

			if ( ! isset( $customizer_option['descr'] ) )
				$customizer_option['descr'] = '';
			
			$wp_customize->add_section( $current_section_id, array(
				'title' => $customizer_option['title'],
				'priority' => $section_priority,
				'description' => $customizer_option['descr']
			) );
			
		} elseif ( $customizer_option['type'] == 'option_color' ) {
			
			/* New Option (COLOR) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;
			
			$wp_customize->add_setting( $current_setting_id, array(
				'default' => $customizer_option['def'],
				'type' => 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );
			
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $current_setting_id, array(
					'label' => $customizer_option['title'],
					'section' => $current_section_id,
					'priority' => $setting_priority
				) ) );
			
		} elseif ( $customizer_option['type'] == 'option_text' ) {
			
			/* New Option (TEXT) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'wp_kses',
			) );
				
				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'type'		=> 'text',
					'priority'	=> $setting_priority
				));
			
		} elseif ( $customizer_option['type'] == 'option_select' ) {
			
			/* New Option (SELECT) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;
			
			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );
				
				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'type'		=> 'select',
					'choices'	=> $customizer_option['opts'],
					'priority'	=> $setting_priority,
				));
			
		} elseif ( $customizer_option['type'] == 'option_checkbox' ) {
			
			/* New Option (checkbox) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;
			
			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );
				
				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'type'		=> 'checkbox',
					'priority'	=> $setting_priority,
				));

		} elseif ( $customizer_option['type'] == 'option_multi_checkbox' ) {
			
			/* New Option (checkbox) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;
			
			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );

				$wp_customize->add_control(
			        new The_Essence_Customize_Control_Checkbox_Multiple(
			            $wp_customize,
			            $current_setting_id,
			            array(
			                'section' => $current_section_id,
			                'label'   => $customizer_option['title'],
			                'choices' => $customizer_option['opts'],
			                'priority'	=> $setting_priority,
			            )
			        )
			    );

		} elseif ( $customizer_option['type'] == 'option_image' ) {
			
			/* New Option (image) */
			
			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;
			
			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			) );
			
				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'priority'	=> $setting_priority,
				) ) );
			
		}
		
	}

} add_action( 'customize_register', 'the_essence_customizer_register' );

/**
 * Load custom controls
 */
function the_essence_load_customize_controls() {

    require_once( trailingslashit( get_template_directory() ) . 'inc/customizer-control-multi-checkbox.php' );

} add_action( 'customize_register', 'the_essence_load_customize_controls', 0 );

