<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( THE_ESSENCE_CMB2_PATH . '/init.php' ) ) {
	require_once THE_ESSENCE_CMB2_PATH . '/init.php';
}

/**
 * Register Post Options
 */
function the_essence_post_options() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_the_essence_';

	// Homepage Options

	$cmb_demo_2 = new_cmb2_box( array(
		'id'            => $prefix . 'metabox_page',
		'title'         => esc_html__( 'Homepage Options', 'the-essence' ),
		'object_types'  => array( 'page', ),
	) );

		$cmb_demo_2->add_field( array(
			'name'       => esc_html__( 'Posts Per Page', 'the-essence' ),
			'desc'       => esc_html__( 'Amount of posts to be displayed per page ( per "load more" ).', 'the-essence' ),
			'id'         => $prefix . 'query_posts_per_page',
			'default'    => 2,
			'type'       => 'text',
		) );

		$cmb_demo_2->add_field( array(
			'name'       => esc_html__( 'Style', 'the-essence' ),
			'desc'       => esc_html__( 'Choose the style for the homepage.', 'the-essence' ),
			'id'         => $prefix . 'homepage_style',
			'default'    => 'sidebar',
			'type'       => 'select',
			'options'    => array(
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
		) );

		$cmb_demo_2->add_field( array(
			'name'       => esc_html__( 'Layout Type', 'the-essence' ),
			'desc'       => esc_html__( 'By default the posts are shown in a regular grid, if you want you can enable masonry grid.', 'the-essence' ),
			'id'         => $prefix . 'homepage_layout_type',
			'default'    => 'grid',
			'type'       => 'select',
			'options'    => array(
				'grid' => 'Grid',
				'masonry' => 'Masonry',
			),
		) );

	/**
	 * Post Options
	 */

	$post_opts = new_cmb2_box( array(
		'id'            => $prefix . 'metabox_post',
		'title'         => esc_html__( 'Post Options', 'the-essence' ),
		'object_types'  => array( 'post', ),
	) );

		$post_opts->add_field( array(
			'name'       => esc_html__( 'Layout', 'the-essence' ),
			'desc'       => esc_html__( 'Choose the layout for this post ( full content or with sidebar ). If set to default it will use the global option from Customize > Blog Post Single.', 'the-essence' ),
			'id'         => $prefix . 'post_layout',
			'type'       => 'select',
			'default'    => 'global',
			'options'    => array(
				'global' => esc_html__( 'Default', 'the-essence' ),
				'full_content' => esc_html__( 'Full Content', 'the-essence' ),
				'content_sidebar' => esc_html__( 'Content + Sidebar', 'the-essence' ),
			),
		) );

} add_action( 'cmb2_admin_init', 'the_essence_post_options' );
