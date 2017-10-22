<?php
/**
 * Table Of Contents
 *
 * the_essence_setup ( sets up theme defaults and registers support for various WordPress features )
 * the_essence_content_width ( set the content width global variable )
 * the_essence_register_sidebars ( register sidebars )
 * the_essence_scripts ( enqueue scripts and styles )
 * the_essence_fonts_url ( returns the google fonts URL )
 * the_essence_admin_scripts ( admin enqueue scripts and styles )
 * the_essence_excerpt ( output post excerpt )
 * include other files
 */

/**
 * global vars
 */

define( 'THE_ESSENCE_SOURCE', 'shop' );
define( 'THE_ESSENCE_THEME_VER', '1.2.3' );
define( 'THE_ESSENCE_CUSTOMIZER_PREPEND', 'the_essence_' );
define( 'THE_ESSENCE_CMB2_PATH', get_template_directory() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'CMB2' );

if ( ! function_exists( 'the_essence_setup' ) ) {

	/**
	 * sets up theme defaults and registers support for various WordPress features
	 *
	 * @since 1.0
	 */
	function the_essence_setup() {
		
		// translation
		load_theme_textdomain( 'the-essence', get_template_directory() . '/languages' );

		// theme support
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		// register menus
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'the-essence' ),
			'top-bar' => esc_html__( 'Top Bar', 'the-essence' ),
			'footer'  => esc_html__( 'Footer', 'the-essence' ),
			'panel'   => esc_html__( 'Side Panel', 'the-essence' ),
		) );

		// general image sizes
		add_image_size( 'the-essence-full', 1255, 99999 ); // used in content-single
		add_image_size( 'the-essence-with-sidebar', 825, 99999 ); // used in content

		// s1 ( ratio 1/1.6 )
		// s1 is used on homepage and search
		add_image_size( 'the-essence-s1-12', 1255, 784, true ); // 12/12 no sidebar
		add_image_size( 'the-essence-s1-6', 610, 381, true ); // 6/12 no sidebar
		add_image_size( 'the-essence-s1-4', 395, 247, true ); // 4/12 no sidebar
		add_image_size( 'the-essence-s1-3', 288, 180, true ); // 3/12 no sidebar
		add_image_size( 'the-essence-s1-s-12', 825, 515, true ); // 12/12 with sidebar
		add_image_size( 'the-essence-s1-s-6', 401, 251, true ); // 6/12 with sidebar
		add_image_size( 'the-essence-s1-s-4', 260, 162, true ); // 4/12 with sidebar
		add_image_size( 'the-essence-s1-s-3', 189, 118, true ); // 3/12 with sidebar
		add_image_size( 'the-essence-s1-retina', 840, 525, true );
		add_image_size( 'the-essence-s1-retina-alt', 600, 375, true );

		// s2 ( ratio 1/2 )
		// s2 is used in horizontal carousel
		add_image_size( 'the-essence-s2', 165, 82, true );
		add_image_size( 'the-essence-s2-retina', 840, 420, true );
		add_image_size( 'the-essence-s2-retina-alt', 600, 300, true );

		// s3 ( ratio 1/1.06 )
		// s3 is used in vertical carousel and homepage
		add_image_size( 'the-essence-s3-12', 1255, 1184, true ); // 12/12 no sidebar
		add_image_size( 'the-essence-s3-6', 610, 575, true ); // 6/12 no sidebar
		add_image_size( 'the-essence-s3-4', 395, 373, true ); // 4/12 no sidebar
		add_image_size( 'the-essence-s3-3', 288, 271, true ); // 3/12 no sidebar
		add_image_size( 'the-essence-s3-s-12', 825, 778, true ); // 12/12 with sidebar
		add_image_size( 'the-essence-s3-s-6', 401, 378, true ); // 6/12 with sidebar
		add_image_size( 'the-essence-s3-s-4', 260, 245, true ); // 4/12 with sidebar
		add_image_size( 'the-essence-s3-s-3', 189, 178, true ); // 3/12 with sidebar
		add_image_size( 'the-essence-s3-retina', 840, 792, true );
		add_image_size( 'the-essence-s3-retina-alt', 600, 566, true );

		// s4 ( ratio 1/1.44 )
		// s4 is used in related posts and homepage
		add_image_size( 'the-essence-s4-12', 1255, 872, true ); // 12/12 no sidebar
		add_image_size( 'the-essence-s4-6', 610, 424, true ); // 6/12 no sidebar
		add_image_size( 'the-essence-s4-4', 395, 274, true ); // 4/12 no sidebar
		add_image_size( 'the-essence-s4-3', 288, 200, true ); // 3/12 no sidebar
		add_image_size( 'the-essence-s4-s-12', 825, 573, true ); // 12/12 with sidebar
		add_image_size( 'the-essence-s4-s-6', 401, 278, true ); // 6/12 with sidebar
		add_image_size( 'the-essence-s4-s-4', 260, 180, true ); // 4/12 with sidebar
		add_image_size( 'the-essence-s4-s-3', 189, 131, true ); // 3/12 with sidebar
		add_image_size( 'the-essence-s4-retina', 840, 583, true );
		add_image_size( 'the-essence-s4-retina-alt', 600, 416, true );

		// s5 ( ratio 1/1.7 )
		// s5 is used in footer posts
		add_image_size( 'the-essence-s5', 272, 160, true ); 
		add_image_size( 'the-essence-s5-retina', 840, 494, true ); 
		add_image_size( 'the-essence-s5-retina-alt', 600, 352, true );

		// s6 ( ratio 1/1 )
		// s6 is used in posts widget
		add_image_size( 'the-essence-small', 85, 85, true );
		add_image_size( 'the-essence-small-retina', 170, 170, true );

		// s7 ( ratio 1/2 )
		// s7 is used in alternate posts widget and homepage
		add_image_size( 'the-essence-s7-12', 1255, 627, true ); // 12/12 no sidebar
		add_image_size( 'the-essence-s7-6', 610, 305, true ); // 6/12 no sidebar
		add_image_size( 'the-essence-s7-4', 395, 197, true ); // 4/12 no sidebar
		add_image_size( 'the-essence-s7-3', 288, 144, true ); // 3/12 no sidebar
		add_image_size( 'the-essence-s7-s-12', 825, 412, true ); // 12/12 with sidebar
		add_image_size( 'the-essence-s7-s-6', 401, 200, true ); // 6/12 with sidebar
		add_image_size( 'the-essence-s7-s-4', 260, 130, true ); // 4/12 with sidebar
		add_image_size( 'the-essence-s7-s-3', 189, 94, true ); // 3/12 with sidebar
		add_image_size( 'the-essence-s7-retina', 840, 420, true );
		add_image_size( 'the-essence-s7-retina-alt', 600, 300, true );

	}

} add_action( 'after_setup_theme', 'the_essence_setup' );

if ( ! function_exists( 'the_essence_content_width' ) ) {

	/**
	 * set the content width global variable
	 *
	 * @since 1.0
	 * @global int $content_width
	 */
	function the_essence_content_width() {
		
		$GLOBALS['content_width'] = apply_filters( 'the_essence_content_width', 1255 );

	}

} add_action( 'after_setup_theme', 'the_essence_content_width', 0 );

if ( ! function_exists( 'the_essence_register_sidebars' ) ) {

	/**
	 * register sidebars
	 *
	 * @since 1.0
	 */
	function the_essence_register_sidebars() {

		// sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'the-essence' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title" data-mtst-selector="#sidebar .widget-title" data-mtst-label="Sidebar - Widget Title">',
			'after_title'   => '</h2>',
		) );

		// footer column 1
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Column 1', 'the-essence' ),
			'id'            => 'sidebar-2',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title" data-mtst-selector="#footer .widget-title" data-mtst-label="Footer - Widget Title">',
			'after_title'   => '</h2>',
		) );

		// footer column 2
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Column 2', 'the-essence' ),
			'id'            => 'sidebar-3',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title" data-mtst-selector="#footer .widget-title" data-mtst-label="Footer - Widget Title">',
			'after_title'   => '</h2>',
		) );

		// footer column 3
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Column 3', 'the-essence' ),
			'id'            => 'sidebar-4',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title" data-mtst-selector="#footer .widget-title" data-mtst-label="Footer - Widget Title">',
			'after_title'   => '</h2>',
		) );

		// side panel
		register_sidebar( array(
			'name'          => esc_html__( 'Side Panel', 'the-essence' ),
			'id'            => 'sidebar-panel',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

	}

} add_action( 'widgets_init', 'the_essence_register_sidebars' );

if ( ! function_exists( 'the_essence_scripts' ) ) {
	
	/**
	 * enqueue scripts and styles
	 *
	 * @since 1.0
	 */
	function the_essence_scripts() {

		// styles
		wp_enqueue_style( 'the-essence-style', get_stylesheet_uri() );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/fonts/font-awesome/font-awesome.css' );
		wp_enqueue_style( 'the-essence-plugins', get_template_directory_uri() . '/css/plugins.css' );

		// scripts
		wp_enqueue_script( 'the-essence-plugins-js', get_template_directory_uri() . '/js/plugins.js', array( 'jquery', 'jquery-effects-core' ), THE_ESSENCE_THEME_VER, true );
		wp_enqueue_script( 'the-essence-main-js', get_template_directory_uri() . '/js/main.js', array(), THE_ESSENCE_THEME_VER, true );

		// google fonts
		wp_enqueue_style( 'the-essence-google-fonts', the_essence_fonts_url(), array(), THE_ESSENCE_THEME_VER );

		// comment reply script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

} add_action( 'wp_enqueue_scripts', 'the_essence_scripts' );

if ( ! function_exists( 'the_essence_fonts_url' ) ) {

	/**
	 * returns the google fonts URL
	 *
	 * @since 1.0
	 */
	function the_essence_fonts_url() {
		
		$font_url = '';

		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$font_state = _x( 'on', 'Google fonts: on or off', 'the-essence' );
		if ( 'off' !== $font_state ) {
			$font_url = add_query_arg( 'family', urlencode( 'Rufina:400,700|Source Sans Pro:400,200,300,400italic,600,700,900&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
		}

		return $font_url;
	}

}

if ( ! function_exists( 'the_essence_admin_scripts' ) ) {
	
	/**
	 * admin enqueue scripts and styles
	 *
	 * @since 1.0
	 */
	function the_essence_admin_scripts() {

		wp_enqueue_style( 'the-essence-admin-css', get_template_directory_uri() . '/css/admin.css' );

	} 

} add_action( 'admin_enqueue_scripts', 'the_essence_admin_scripts' );

function the_essence_excerpt( $length = 130 ) {

	$excerpt = get_the_excerpt();
	$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $length);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	$excerpt = $excerpt.'.';
	echo $excerpt;

}

function the_essence_body_class( $classes ) {

	if ( the_essence_get_theme_mod( 'capitalize_letter', 'enabled' ) == 'enabled' ) {
		$classes[] = 'body-capitalize-letter-enabled';
	}

	return $classes;

} add_filter( 'body_class', 'the_essence_body_class' );

// include TGM init ( for plugin activate )
include get_template_directory() . '/inc/tgm/tgm-init.php';

// include frameworks and options
include get_template_directory() . '/inc/theme-options.php';
include get_template_directory() . '/inc/user-options.php';
include get_template_directory() . '/inc/post-options.php';

// include other functions
include get_template_directory() . '/inc/functions.php';
include get_template_directory() . '/inc/display-functions.php';
include get_template_directory() . '/inc/welcome.php';
include get_template_directory() . '/inc/importer/init.php';

// include widgets
include get_template_directory() . '/inc/widgets/widget.author.php';
include get_template_directory() . '/inc/widgets/widget.posts.php';
include get_template_directory() . '/inc/widgets/widget.posts-alt.php';
include get_template_directory() . '/inc/widgets/widget.subscribe.php';
include get_template_directory() . '/inc/widgets/widget.social.php';
include get_template_directory() . '/inc/widgets/widget.instagram.php';
include get_template_directory() . '/inc/widgets/widget.call-to-action.php';

// updates
if ( THE_ESSENCE_SOURCE == 'themeforest' ) {
	include get_template_directory() . '/inc/tf-updates.php';
} else {
	function the_essence_theme_updater() {
		require( get_template_directory() . '/inc/mt-updater.php' );
	} add_action( 'after_setup_theme', 'the_essence_theme_updater' );
}