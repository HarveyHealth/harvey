<?php
/**
 * Plugin Name: Simple Custom CSS and JS PRO
 * Plugin URI: http://www.silkypress.com/simple-custom-css-js-pro/
 * Description: Easily add Custom CSS or JS to your website with an awesome editor.
 * Version: 3.9
 * Author: SilkyPress.com
 * Author URI: http://www.silkypress.com
 *
 * Text Domain: custom-css-js-pro
 * Domain Path: /languages/
 *
 * WC requires at least: 2.3.0
 * WC tested up to: 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'ccj_plugin_updater_data' ) ) {
    function ccj_plugin_updater_data() {
        return array(
            'plugin_server' => 'https://www.silkypress.com',
            'file' => __FILE__,
            'data' => array(
               'version' => '3.9',
               'plugin_name' => 'Simple Custom CSS and JS PRO',
               'author' => 'Diana Burduja',
            ),
        );
    }
}

if ( ! class_exists( 'CustomCSSandJSpro' ) ) :
/**
 * Main CustomCSSandJS Class
 *
 * @class CustomCSSandJS
 */
final class CustomCSSandJSpro {
    public $search_tree = false;
    public $allowed_codes = true;
    protected static $_instance = null;

    /**
     * Main CustomCSSandJS Instance
     *
     * Ensures only one instance of CustomCSSandJS is loaded or can be loaded
     *
     * @static
     * @return CustomCSSandJS - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
      * Cloning is forbidden.
      */
    public function __clone() {
         _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0' );
    }

    /**
     * CustomCSSandJS Constructor
     * @access public
     */
    public function __construct() {

        add_action( 'init', array( $this, 'register_post_type' ) );
        $this->set_constants();

        include_once( 'includes/functions.php' );

        if ( is_admin() ) {
            $this->load_plugin_textdomain();
            add_action('admin_init', array( $this, 'remove_menu_link') );
            include_once( 'includes/admin-screens.php' );
            if ( get_option( 'ccj_license_status' ) == 'valid' ) {
                include_once( 'includes/admin-preview.php' );
                include_once( 'includes/admin-url-rules.php' );
                //include_once( 'includes/admin-url-rules2.php' );
                include_once( 'includes/admin-revisions.php' );
                include_once( 'includes/admin-import.php' );
                include_once( 'includes/admin-shortcodes.php' );
            } else {
                add_action('add_meta_boxes', array( $this, 'notice_activate_license' ) );
            }
                include_once( 'includes/admin-choose-theme.php' );
                include_once( 'includes/admin-config.php' );
                include_once( 'includes/admin-warnings.php' );
        }

        if ( is_admin() ) {
            // In the admin side the `init` hooked is needed to show the codes on all the pages, not only Posts/Pages pages
            add_action( 'init', array( $this, 'initiate_show_codes' ) );
        } else {
            // In the frontend the `wp` hooked is needed. The `init` hooked fires too early for the WP Conditional Tags to take effect
            add_action( 'wp', array( $this, 'initiate_show_codes' ) );
        }
    }


    /**
     * Initiate the procedure for showing the codes
     */
    function initiate_show_codes() {

        include_once( 'includes/class-show-codes.php' );

        $show_codes = new CustomCSSandJS_ShowCodesPro();
        $show_codes->set_value('first_page', home_url()  );
        $show_codes->set_value('upload_dir', CCJ_UPLOAD_DIR );
        $show_codes->set_value('upload_url', CCJ_UPLOAD_URL );

        // Get the allowed codes for this specific page
        $uri = $_SERVER['REQUEST_URI'];
        $ccj_urls = get_option( 'custom-css-js-urls' );
        $allowed_codes = $show_codes->url_rules( $uri, $ccj_urls );
        $allowed_codes = $show_codes->wp_conditional_tags( $allowed_codes, $ccj_urls );

        // Print shortcodes
        $search_tree = get_option( 'custom-css-js-tree' );
        $show_codes->set_value('search_tree_raw', $search_tree );
        $show_codes->add_shortcodes();

        // Get the filtered search tree
        $search_tree = $show_codes->filter_search_tree( $search_tree, $allowed_codes );
        if ( isset($_GET['ccj-preview-id'] ) ) {
            $preview_id = $_GET['ccj-preview-id'];
            $search_tree = $show_codes->search_tree_for_preview( $search_tree, $preview_id );
        }

        // Print the codes
        $show_codes->set_value('search_tree', $search_tree );
        $show_codes->print_code_actions( $search_tree );
    }


    /**
     * Remove the "Custom JS & CSS" link in the menu for non-admins
     */
    function remove_menu_link() {
        global $menu;

        if (!is_array($menu) || count($menu) == 0 )
            return false;

        if ( ! current_user_can( 'activate_plugins' ) ) {
            remove_menu_page('edit.php?post_type=custom-css-js');
        }
    }

    /**
     * Add a meta box to remind to activate the license
     */
    function notice_activate_license() {
        add_meta_box( 'activatelicensediv', __('Please activate the license', 'custom-css-js-pro'), array( $this, 'activate_license_meta_box_callback' ), 'custom-css-js', 'normal' );
    }


    /**
     * The meta box content
     */
    function activate_license_meta_box_callback( $post ) {
        ?>
        <div id="activatelicense-action">
            <?php printf( __('Don\'t forget to activate <a href="%s">here</a> the license key you received in the email.', 'customo-css-js-pro'), 'edit.php?post_type=custom-css-js&page=custom-css-js-config&tab=license' ); ?>
        </div>
        <?php

    }



    /**
     * Set constants for later use
     */
    function set_constants() {
        $dir = wp_upload_dir();
        $constants = array(
            'CCJ_PREVIEW_PREFIX'  => 'ccj_preview-',
            'CCJ_VERSION_PRO'         => '3.9',
            'CCJ_UPLOAD_DIR'      => $dir['basedir'] . '/custom-css-js',
            'CCJ_UPLOAD_URL'      => $dir['baseurl'] . '/custom-css-js',
            'CCJ_PLUGIN_FILE_PRO'     => __FILE__,
        );
        foreach( $constants as $_key => $_value ) {
            if (!defined($_key)) {
                define( $_key, $_value );
            }
        }
    }


    /**
     * Create the custom-css-js post type
     */
    public function register_post_type() {
        $labels = array(
            'name'               => _x( 'Custom Code', 'post type general name', 'custom-css-js-pro'),
            'singular_name'      => _x( 'Custom Code', 'post type singular name', 'custom-css-js-pro'),
            'menu_name'          => _x( 'Custom CSS & JS', 'admin menu', 'custom-css-js-pro'),
            'name_admin_bar'     => _x( 'Custom Code', 'add new on admin bar', 'custom-css-js-pro'),
            'add_new'            => _x( 'Add Custom Code', 'add new', 'custom-css-js-pro'),
            'add_new_item'       => __( 'Add Custom Code', 'custom-css-js-pro'),
            'new_item'           => __( 'New Custom Code', 'custom-css-js-pro'),
            'edit_item'          => __( 'Edit Custom Code', 'custom-css-js-pro'),
            'view_item'          => __( 'View Custom Code', 'custom-css-js-pro'),
            'all_items'          => __( 'All Custom Code', 'custom-css-js-pro'),
            'search_items'       => __( 'Search Custom Code', 'custom-css-js-pro'),
            'parent_item_colon'  => __( 'Parent Custom Code:', 'custom-css-js-pro'),
            'not_found'          => __( 'No Custom Code found.', 'custom-css-js-pro'),
            'not_found_in_trash' => __( 'No Custom Code found in Trash.', 'custom-css-js-pro')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Custom CSS and JS code', 'custom-css-js-pro'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'     => 100,
            'menu_icon'         => 'dashicons-plus-alt',
            'query_var'          => false,
            'rewrite'            => array( 'slug' => 'custom-css-js' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'revisions' )
        );

        register_post_type( 'custom-css-js', $args );
    }

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'custom-css-js-pro', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

}

endif;

/**
 * Returns the main instance of CustomCSSandJS
 *
 * @return CustomCSSandJS
 */
function CustomCSSandJSpro() {
    return CustomCSSandJSpro::instance();
}

CustomCSSandJSpro();



/**
 * Register activation hook
 */
function custom_css_js_pro_activation() {
    $ccj_urls = get_option( 'custom-css-js-urls' );

    if ( $ccj_urls !== false ) {
        return false;
    }

    // get all codes
    $posts = query_posts( 'post_type=custom-css-js&post_status=publish&nopaging=true' );

    // return if no codes available
    if ( ! is_array( $posts ) || count( $posts ) == 0 ) {
        return false;
    }

    $ids = array();
    foreach ( $posts as $_post ) {
        if ( get_post_meta( $_post->ID, '_active', true ) === 'no' ) {
            // return if the code is not active
            continue;
        }
        $options = ccj_get_options( $_post->ID );
        if ( ! isset( $options['language'] ) ) continue;
        if ( $options['language'] ==  'html' ) {
            $file = $_post->ID;
        } else {
            $file = $_post->ID . '.' . $options['language'];
        }
        $ids[] = $file;
    }
    // create the custom-css-js-urls option
    update_option( 'custom-css-js-urls', array('all' => $ids ) );
}
register_activation_hook( __FILE__, 'custom_css_js_pro_activation' );


/**
 * Plugin action link to Settings page
*/
function custom_css_js_pro_plugin_action_links( $links ) {

    $settings_link = '<a href="edit.php?post_type=custom-css-js&page=custom-css-js-config">' .
        esc_html( __('Settings' ) ) . '</a>';

    return array_merge( array( $settings_link), $links );

}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'custom_css_js_pro_plugin_action_links' );
