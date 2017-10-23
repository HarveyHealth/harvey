<?php
/**
 * Custom CSS and JS
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * CustomCSSandJS_AdminConfig 
 */
class CustomCSSandJS_AdminConfigPro {

    var $settings_default;

    var $settings;

    /**
     * Constructor
     */
    public function __construct() {
        // Get the "default settings"
        $settings_default = apply_filters( 'ccj_settings_default', array() );

        // Get the saved settings
        $settings = get_option('ccj_settings');
        if ( $settings == false ) {
            $settings = $settings_default;
        } else {
            foreach( $settings_default as $_key => $_value ) {
                if ( ! isset($settings[$_key] ) ) {
                    $settings[$_key] = $_value;
                }
            }
        }
        $this->settings = $settings;
        $this->settings_default = $settings_default;

        //Add actions and filters
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }


    /**
     * Add submenu pages
     */
    function admin_menu() {
        $menu_slug = 'edit.php?post_type=custom-css-js';

        add_submenu_page( $menu_slug, __('Settings', 'custom-css-js-pro'), __('Settings', 'custom-css-js-pro'), 'manage_options', 'custom-css-js-config', array( $this, 'config_page' ) );

    }


    /**
     * Enqueue the scripts and styles
     */
    public function admin_enqueue_scripts( $hook ) {

        $screen = get_current_screen();

        // Only for custom-css-js post type
        if ( $screen->post_type != 'custom-css-js' ) 
            return false;

        if ( $hook != 'custom-css-js_page_custom-css-js-config' ) 
            return false;

        // Some handy variables
        $a = plugins_url( '/', CCJ_PLUGIN_FILE_PRO). 'assets';
        $v = CCJ_VERSION_PRO; 

        wp_enqueue_script( 'tipsy', $a . '/jquery.tipsy.js', array('jquery'), $v, false );
        wp_enqueue_style( 'tipsy', $a . '/tipsy.css', array(), $v );
    }



    /**
     * Template for the config page
     */
    function config_page() {

        if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'license' ) {
            $this->license_tab();
            return;
        }

        if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'debug' ) {
            $this->debug_tab();
            return;
        }

        if ( isset( $_POST['ccj_settings-nonce'] ) ) {
            check_admin_referer('ccj_settings', 'ccj_settings-nonce');

            $data = apply_filters( 'ccj_settings_save', array() );

            update_option( 'ccj_settings', $data );

        } else {
            $data = $this->settings;
        }

        ?>
        <div class="wrap">

        <?php $this->config_page_header('general'); ?>

        <form action="<?php echo admin_url('edit.php'); ?>?post_type=custom-css-js&page=custom-css-js-config" id="ccj_settings" method="post">
        <table class="form-table">
                <?php do_action( 'ccj_settings_form' ); ?>

            <tr>
            <th>&nbsp;</th>
            <td>
            <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save'); ?>" />
            <?php wp_nonce_field('ccj_settings', 'ccj_settings-nonce', false); ?>
            </td>
            </tr>

        </table>
        </form>
        </div>
        <?php
    }


    /**
     * Template for config page header 
     */
    function config_page_header( $tab = 'general' ) {
  
        $url = '?post_type=custom-css-js&page=custom-css-js-config';

        $active = array('general' => '', 'license' => '', 'debug' => '');
        $active[$tab] = 'nav-tab-active';

        ?>
        <h1><?php _e('Custom CSS & JS PRO'); ?></h1>

        <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="<?php echo $url; ?>" class="nav-tab <?php echo $active['general']; ?>"><?php echo __('General Settings', 'custom-css-js-pro'); ?></a>
            <a href="<?php echo $url; ?>&tab=license" class="nav-tab <?php echo $active['license']; ?>"><?php echo __('License Key', 'custom-css-js-pro'); ?></a>
            <a href="<?php echo $url; ?>&tab=debug" class="nav-tab <?php echo $active['debug']; ?>"><?php echo __('Debug', 'custom-css-js-pro'); ?></a>
        </h2>

        <?php     
    }


    /** 
     * Template for the License tab in the config page
     */
    function license_tab() {

        require_once(dirname(__FILE__) . '/edd/edd-plugin.php');

        $a = ccj_plugin_updater_data();

        $license_data = array(
            'store_url'     => $a['plugin_server'], 
            'item_name'     => $a['data']['plugin_name'], 
            'author'        => $a['data']['author'], 
            'version'       => $a['data']['version'], 
            'main_file'     => $a['file'], 
            'prefix'        => 'ccj_',
            'license'       => 'ccj_license',
            'license_key'   => 'ccj_license_key',
            'license_status' => 'ccj_license_status',
        );

        $edd = new CustomCSSandJS_LicenseForm( $license_data );

        if ( ! empty( $_POST ) ) {
            $response = $edd->activate_deactivate_license( $_POST );
        }


        ?>
        <div class="wrap">

        <?php $this->config_page_header('license'); ?>

        <?php $edd->license_page(); ?>

        </div>

        <?php
    }


    /** 
     * Template for the Debug tab in the config page
     */
    function debug_tab() {

        $ccj_for_url = '';
        if ( isset( $_POST['ccj_for_url'] ) ) {
            $ccj_for_url = $_POST['ccj_for_url'];
        }

        include_once( 'class-show-codes.php' );

        $show_codes = new CustomCSSandJS_ShowCodesPro(); 
        $show_codes->set_value('first_page', get_option('home') );
        $show_codes->set_value('upload_dir', CCJ_UPLOAD_DIR );
        $show_codes->set_value('upload_url', CCJ_UPLOAD_URL );

        // Get the allowed codes for this specific page
        $uri = $ccj_for_url;

        $ccj_urls = get_option( 'custom-css-js-urls' );
        $allowed_codes = $show_codes->url_rules( $uri, $ccj_urls );

        $search_tree = get_option( 'custom-css-js-tree' );
        $filtered_search_tree = $show_codes->filter_search_tree( $search_tree, $allowed_codes ); 

        // WordPress environment
        $report = '';
        $report .= 'Home URL: ' . get_option( 'home' ) . PHP_EOL;
        $report .= 'Site URL: ' . get_option( 'siteurl' ) . PHP_EOL;
        $report .= 'WP Version: ' . get_bloginfo('version') . PHP_EOL;
        $report .= 'CCJ Version: ' . CCJ_VERSION_PRO . PHP_EOL;
        $report .= 'WP Multisite: ' . var_export(is_multisite(), true). PHP_EOL;
        $report .= 'WP Memory Limit: ' . @ini_get( 'memory_limit' ). PHP_EOL;

        ob_start(); 
        echo '### WordPress Environment ###'. PHP_EOL;
        echo $report . PHP_EOL;
        if ( ! empty( $ccj_for_url ) ) {
            echo '### Report generated for: ' . PHP_EOL;
            echo '   ' . $ccj_for_url . PHP_EOL;
        }
        echo '### All codes ###' . PHP_EOL;
        print_r( $ccj_urls );
        if ( ! empty( $ccj_for_url ) ) {
            echo '### Allowed codes ###' . PHP_EOL;
            print_r( $allowed_codes );
        }
        echo '### Search tree ###' . PHP_EOL;
        print_r( $search_tree );
        if ( ! empty( $ccj_for_url ) ) {
            echo '### Filtered Search tree ###' . PHP_EOL;
            print_r( $filtered_search_tree );
        }
        $report = ob_get_contents();
        ob_end_clean();

        ?>

<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {

        $("#debug-report textarea").focus().select();

    });
        </script>

        <div class="wrap">

        <?php $this->config_page_header('debug'); ?>

        <div id="debug-report" style="display: block;">
            <div style="margin-bottom: 10px;"><?php _e('Please copy and paste this information in your ticket when contacting support', 'custom-css-js-pro'); ?>:</div>
            <textarea readonly="readonly"><?php echo $report; ?></textarea>


            <form method="post">
            <table class="form-table">
                <tr>
                <td>
                <input type="text" name="ccj_for_url" value="<?php echo $ccj_for_url; ?>" style="width: 100%;" placeholder="<?php _e('URL for which you want to generate the report', 'custom-css-js-pro'); ?>" />
                </td><td>
                <input type="submit" name="Submit" class="button-primary" value="<?php _e('Regenerate the debug info', 'custom-css-js-pro'); ?>" />
                </td>
                </tr>
            </table>
            </form>


        </div>
        </div>

        <?php
    }

}

return new CustomCSSandJS_AdminConfigPro();
