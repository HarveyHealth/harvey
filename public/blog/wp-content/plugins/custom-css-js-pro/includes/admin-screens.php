<?php
/**
 * Custom CSS and JS
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * CustomCSSandJS_Admin
 */
class CustomCSSandJS_AdminPro {

    /**
     * Array with the options for a specific custom-css-js post
     */
    private $options = array();

    /**
     * The editor's theme
     */
    private $theme = '';

    /**
     * Constructor
     */
    public function __construct() {

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $free_version = 'custom-css-js/custom-css-js.php';
        if ( is_plugin_active( $free_version ) ) {
            deactivate_plugins( $free_version );
            return false;
        }

        $this->add_functions();
        $this->edd_updater();
    }

    /**
     * Add actions and filters
     */
    function add_functions() {

        // Add filters
        $filters = array(
            'manage_custom-css-js_posts_columns' => 'manage_custom_posts_columns',
        );
        foreach( $filters as $_key => $_value ) {
            add_filter( $_key, array( $this, $_value ) );
        }

        // Add actions
        $actions = array(
            'admin_menu' => 'admin_menu',
            'admin_enqueue_scripts' => 'admin_enqueue_scripts',
            'current_screen' => 'current_screen',
            'admin_notices' => 'create_uploads_directory',
            'edit_form_after_title' => 'codemirror_editor',
            'add_meta_boxes' => 'add_meta_boxes',
            'save_post' => 'options_save_meta_box_data',
            'trashed_post' => 'trash_post',
            'untrashed_post' => 'trash_post',
            'admin_post_ccj-autosave' => 'ajax_autosave',
            'wp_loaded'             => 'compatibility_shortcoder',
            'wp_ajax_ccj_active_code' => 'wp_ajax_ccj_active_code',
            'post_submitbox_start'      => 'post_submitbox_start',
        );
        foreach( $actions as $_key => $_value ) {
            add_action( $_key, array( $this, $_value ) );
        }


        $settings = get_option('ccj_settings');
        $this->theme = isset($settings['ccj_editor_theme']) ? $settings['ccj_editor_theme'] : '';
        if ( $this->theme == 'default' ) {
            $this->theme = '';
        }


        // Add some custom actions
        add_action( 'manage_custom-css-js_posts_custom_column', array( $this, 'manage_posts_columns' ), 10, 2 );
        add_action( 'save_post', array( $this, 'revision_save_meta_box_data' ), 10, 2 );
        add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
    }

    function ajax_autosave() {

        check_admin_referer( 'ccj-nonce' );

        if ( ! isset( $_POST['content'] ) || ! isset( $_POST['title'] ) ) {
            $ajax = new WP_AJAX_Response( array('data' => -1 ) );
            $ajax->send();
            return;
        }

        if ( isset( $_POST['data'] ) ) {
            wp_autosave( $_POST );
            $ajax = new WP_AJAX_Response( array('data' => $_POST['data'] . ' => ' . $_POST['data2'] ) );
            $ajax->send();
            return;
        }
    }


    /**
     * Add submenu pages
     */
    function admin_menu() {
        $menu_slug = 'edit.php?post_type=custom-css-js';
        $submenu_slug = 'post-new.php?post_type=custom-css-js';

        $title = __('Add Custom CSS', 'custom-css-js-pro');
        add_submenu_page( $menu_slug, $title, $title, 'manage_options', $submenu_slug .'&language=css');

        $title = __('Add Custom JS', 'custom-css-js-pro');
        add_submenu_page( $menu_slug, $title, $title, 'manage_options', $submenu_slug . '&language=js');

        $title = __('Add Custom HTML', 'custom-css-js-pro');
        add_submenu_page( $menu_slug, $title, $title, 'manage_options', $submenu_slug . '&language=html');

        remove_submenu_page( $menu_slug, $submenu_slug);

    }


    /**
     * Enqueue the scripts and styles
     */
    public function admin_enqueue_scripts( $hook ) {

        $screen = get_current_screen();

        // Only for custom-css-js post type
        if ( $screen->post_type != 'custom-css-js' )
            return false;

        // Some handy variables
        $a = plugins_url( '/', CCJ_PLUGIN_FILE_PRO). 'assets';
        $cm = $a . '/codemirror';
        $v = CCJ_VERSION_PRO;


        // If Toolset Types plugin is enabled, remove the codemirror script
        global $wp_scripts;
        if ( is_plugin_active( 'types/wpcf.php' ) ) {
            if ( isset( $wp_scripts->registered['toolset-codemirror-script'] ) ) {
                unset( $wp_scripts->registered['toolset-codemirror-script'] );
            }
        }


        // Enqueue scripts and styles
        wp_enqueue_script( 'tipsy', $a . '/jquery.tipsy.js', array('jquery'), $v, false );
        wp_enqueue_style( 'tipsy', $a . '/tipsy.css', array(), $v );
        wp_enqueue_script( 'cookie', $a . '/js.cookie.js', array('jquery'), $v, false );
        wp_register_script( 'ccj_admin', $a . '/ccj_admin.js', array('jquery', 'tipsy', 'jquery-ui-resizable'), $v, false );
        wp_localize_script( 'ccj_admin', 'CCJ', $this->cm_localize() );
        wp_enqueue_script( 'ccj_admin' );
        wp_enqueue_style( 'ccj_admin', $a . '/ccj_admin.css', array(), $v );

        // Only for the new/edit Code's page
        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css', array(), $v );
            wp_enqueue_script( 'addon_merge_match_patch', $cm . '/diff_match_patch.js', array( 'jquery' ), $v, false );
            wp_enqueue_script( 'codemirror', $cm . '/codemirror-compressed.js', array( 'jquery' ), $v, false);

            wp_enqueue_style( 'codemirror', $cm . '/codemirror-compressed.css', array(), $v );
            wp_enqueue_script( 'ccj_admin_url_rules', $a . '/ccj_admin-url_rules.js', array('jquery'), $v, false );

            // Add the language modes
            $cmm = $cm . '/mode/';
            wp_enqueue_script('cm-xml', $cmm . 'xml/xml.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-js', $cmm . 'javascript/javascript.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-css', $cmm . 'css/css.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-htmlmixed', $cmm . 'htmlmixed/htmlmixed.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-clike', $cmm . 'clike/clike.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-php', $cmm . 'php/php.js', array('codemirror'), $v, false);


            // Add the search addons
            $cma = $cm . '/addon/';
            wp_enqueue_script('cm-dialog', $cma . 'dialog.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-search', $cma . 'search.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-searchcursor', $cma . 'searchcursor.js', array('codemirror'), $v, false);
            wp_enqueue_script('cm-jump-to-line', $cma . 'jump-to-line.js', array('codemirror'), $v, false);
            wp_enqueue_style('cm-matchesonscrollbar', $cma . 'matchesonscrollbar.css', array(), $v );
            wp_enqueue_style('cm-dialog', $cma . 'dialog.css', array(), $v );
            wp_enqueue_style('cm-matchesonscrollbar', $cma . 'matchesonscrollbar.css', array(), $v );

            // Add scrollbars
            wp_enqueue_script( 'ccj_scrollbars', $cm . '/addon/scroll/simplescrollbars.js', array('codemirror'), $v, false );
            wp_enqueue_style( 'ccj_scrollbars', $cm . '/addon/scroll/simplescrollbars.css', array(), $v );


            wp_enqueue_script( 'ccj_formatting', $cm . '/lib/util/formatting.js', array('codemirror'), $v, false );

            // remove the assets from other plugins so it doesn't interfere with CodeMirror
            global $wp_scripts;
            if (is_array($wp_scripts->registered) && count($wp_scripts->registered) != 0) {
              foreach($wp_scripts->registered as $_key => $_value) {
                if (!isset($_value->src)) continue;

                if (strstr($_value->src, 'wp-content/plugins') !== false && strstr($_value->src, 'plugins/custom-css-js-pro/assets') === false) {
                  unset($wp_scripts->registered[$_key]);
                }
              }
            }
        }

        // Load the editor's theme
        if ( !empty($this->theme) ) {
            wp_enqueue_style( 'cmt-' . $this->theme, $cm . '/theme/' . $this->theme . '.css', array(), $v );
        }
    }


    /**
     * Send variables to the ccj_admin.js script
     */
    public function cm_localize() {

        $vars = array(
            'scroll' => '1',
            'theme' => $this->theme,
            'active' => __('Active', 'custom-css-js-pro'),
            'inactive' => __('Inactive', 'custom-css-js-pro'),
            'activate' => __('Activate', 'custom-css-js-pro'),
            'deactivate' => __('Deactivate', 'custom-css-js-pro'),
            'active_title' => __('The code is active. Click to deactivate it', 'custom-css-js-pro'),
            'deactive_title' => __('The code is inactive. Click to activate it', 'custom-css-js-pro'),
        );

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        // Plugins which conflict with the CodeMirror editor
        $conflicting_plugins = array(
            'types/wpcf.php',
            'advanced-ads-code-highlighter/advanced-ads-code-highlighter.php',
            'wp-custom-backend-css/wp-custom-backend-css.php',
            'custom-css-whole-site-and-per-post/h5ab-custom-styling.php',
            'html-editor-syntax-highlighter/html-editor-syntax-highlighter.php',
        );

        foreach( $conflicting_plugins as $_plugin ) {
            if ( is_plugin_active( $_plugin ) ) {
                $vars['scroll'] = '0';
            }
        }

        return $vars;
    }


    public function add_meta_boxes() {
        $post_id = isset($_GET['post']) ? esc_attr($_GET['post']) : false;
        $language = $this->get_language($post_id);

        if ( $language == 'html' ) {
            add_meta_box('custom-code-options', __('Options ', 'custom-css-js-pro') . ccj_a_doc('https://www.silkypress.com/simple-custom-css-js-pro-documentation/#doc-options'), array( $this, 'custom_code_options_meta_box_callback_html'), 'custom-css-js', 'side', 'low');
        } else {
            add_meta_box('custom-code-options', __('Options', 'custom-css-js-pro'), array( $this, 'custom_code_options_meta_box_callback'), 'custom-css-js', 'side', 'low');
        }

        remove_meta_box( 'slugdiv', 'custom-css-js', 'normal' );
    }


    /**
     * Reformat the `edit` or the `post` screens
     */
    function current_screen( $current_screen ) {

        if ( $current_screen->post_type != 'custom-css-js' ) {
            return false;
        }

        if ( $current_screen->base == 'post' ) {
            add_action( 'admin_head', array( $this, 'current_screen_post' ) );
        }

        if ( $current_screen->base == 'edit' ) {
            add_action( 'admin_head', array( $this, 'current_screen_edit' ) );
        }
    }



    /**
     * Add the buttons in the `edit` screen
     */
    function add_new_buttons() {
        $current_screen = get_current_screen();

        if ( (isset($current_screen->action ) && $current_screen->action == 'add') || $current_screen->post_type != 'custom-css-js' ) {
            return false;
        }
?>
    <div class="updated buttons">
    <a href="post-new.php?post_type=custom-css-js&language=css" class="custom-btn custom-css-btn"><?php _e('Add CSS code', 'custom-css-js-pro'); ?></a>
    <a href="post-new.php?post_type=custom-css-js&language=js" class="custom-btn custom-js-btn"><?php _e('Add JS code', 'custom-css-js-pro'); ?></a>
    <a href="post-new.php?post_type=custom-css-js&language=html" class="custom-btn custom-js-btn"><?php _e('Add HTML code', 'custom-css-js-pro'); ?></a>
    </div>
<?php
    }



    /**
     * Add new columns in the `edit` screen
     */
    function manage_custom_posts_columns( $columns ) {
        return array(
            'cb'    => '<input type="checkbox" />',
            'active' => '<span class="ccj-dashicons dashicons dashicons-star-empty" title="'.__('Active', 'custom-css-js-pro').'"></span>',
            'type'  => __('Type', 'custom-css-js-pro'),
            'title' => __('Title', 'custom-css-js-pro'),
            'author'  => __('Author', 'custom-css-js-pro'),
            //'date'  => __('Date', 'custom-css-js-pro'),
            'published' => __('Date', 'custom-css-js-pro'),
            'modified'  => __('Modified', 'custom-css-js-pro'),
            'options' => __('Options', 'custom-css-js-pro'),
        );
    }


    /**
     * Fill the data for the new added columns in the `edit` screen
     */
    function manage_posts_columns( $column, $post_id ) {

        $options = ccj_get_options( $post_id );

        if ( $column == 'type' ) {
            echo '<span class="language language-'.$options['language'].'">' . $options['language'] . '</span>';
        }

        if ( $column == 'modified' || $column == 'published' ) {
            if ( ! isset( $wp_version ) ) {
                include( ABSPATH . WPINC . '/version.php' );
            }

            if ( version_compare( $wp_version, '4.6', '>=' ) ) {
                if ( $column == 'modified' ) {
                    $m_time = get_the_modified_time( 'G', $post_id );
                } else {
                    $m_time = get_the_time( 'G', $post_id );
                }
            } else {
                if ( $column == 'modified' ) {
                    $m_time = get_the_modified_time( 'G', false, $post_id );
                } else {
                    $m_time = get_the_time( 'G', $post_id );
                }
            }

            $time_diff = time() - $m_time;

            if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
                $h_time = sprintf( __( '%s ago', 'custom-css-js-pro' ), human_time_diff( $m_time ) );
            } else {
                $h_time = mysql2date( get_option('date_format'), $m_time );
            }

            echo $h_time;
        }

        if ( $column == 'active' ) {
            $url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post_id ), 'ccj-active-code-' .$post_id );
            if ( $this->is_active( $post_id ) ) {
                $active_title = __('The code is active. Click to deactivate it', 'custom-css-js-pro');
                $active_icon = 'dashicons-star-filled';
            } else {
                $active_title = __('The code is inactive. Click to activate it', 'custom-css-js-pro');
                $active_icon = 'dashicons-star-empty ccj_row';
            }
            echo '<a href="' . esc_url( $url ) . '" class="ccj_activate_deactivate" data-code-id="'.$post_id.'" title="'.$active_title . '">' .
                '<span class="dashicons '.$active_icon.'"></span>'.
                '</a>';
        }

        if ( $column == 'options' ) {
            echo $this->options_overview( $post_id, $options );
        }
    }

    /**
     * Show the active options as small icons with tooltips
     */
    function options_overview( $post_id, $options ) {

        if ( $options['language'] == 'html' ) {
            $options_array = ccj_get_options_meta_html();
        } else {
            $options_array = ccj_get_options_meta();
        }

        $output = '';


        if ( $options['language'] == 'html' && $options['type'] == 'shortcode' ) {
            $output .= '<span class="dashicons dashicons-paperclip" rel="tipsy" title="'.__('Where on the page: As shortcode', 'custom-css-js-pro').'"></span> ';
            if ( !isset($options['name']) || empty($options['name'])) {
                return $output . __(' Shortcode without id', 'custom-css-js-pro');
            } else {
                return $output .' [ccj id="'.$options['name'].'"]';
            }
            return;
        }


        foreach( $options_array as $_key => $meta ) {
            if ( ! isset( $options[$_key] ) ) {
                continue;
            }
            $_value = $options[$_key];


            if ( $_key == 'priority' ) {
                echo '<span rel="tipsy" title="'. sprintf(__('Priority %d', 'custom-css-js-pro'), $_value) .'">pr' . $_value . '</span>';
                continue;
            }

            // For radio values with a dashicon
            if ( isset($meta['values'][$_value] ) && isset( $meta['values'][$_value]['dashicon']) ) {
                $icon = 'dashicons ';
                if ( isset( $meta['values'][$_value]['dashicon'] ) ) {
                    $icon .= 'dashicons-' . $meta['values'][$_value]['dashicon'];
                }

                $title = $meta['title'];
                if ( isset( $meta['values'][$_value]['title'] ) ) {
                    $title .= ' : ' . $meta['values'][$_value]['title'];
                }

                $output .= '<span class="' . $icon . '" rel="tipsy" title="'.$title.'"></span> ';
            }
            // For radio values without a dashicon
            elseif ( isset($meta['values'][$_value] ) ) {
                if ( $_value == 'none' ) {
                    continue;
                }
                if ( isset( $meta['values'][$_value]['title'] ) ) {
                    $text = $meta['values'][$_value]['title'];
                    $title = $meta['title'] . ' : ' . $text;
                    $output .= ' <span rel="tipsy" title="'.$title.'">'.$text.'</span> ';
                } else {
                    $text = $meta['values'][$_value];
                    $output .= '<span class="dashicons dashicons-' . $meta['dashicon']. '" rel="tipsy" title="'.$text.'"></span> ';
                }
            }
            // For checkboxes
            elseif ( $_value == true ) {
                $title = $meta['title'];
                $icon = '';
                if ( isset( $meta['dashicon'] ) ) {
                    $icon = 'dashicons dashicons-' . $meta['dashicon'];
                }
                $output .= '<span class="' . $icon . '" rel="tipsy" title="'.$title.'"></span> ';
            }

        }

        return $output;
    }


    /**
     * Activate/deactivate a code
     *
     * @return void
     */
    function wp_ajax_ccj_active_code() {
        if ( ! isset( $_GET['code_id'] ) ) die();

        $code_id = absint( $_GET['code_id'] );

        $response = 'error';
		if ( check_admin_referer( 'ccj-active-code-' . $code_id) ) {

			if ( 'custom-css-js' === get_post_type( $code_id ) ) {
                $active = get_post_meta($code_id, '_active', true );
                if ( $active === false || $active === '' ) {
                    $active = 'yes';
                }
                $response = $active;
				update_post_meta( $code_id, '_active', $active === 'yes' ? 'no' : 'yes' );

                ccj_build_search_tree();
			}
		}
        echo $response;

		die();
    }


    /**
     * Check if a code is active
     *
     * @return bool
     */
    function is_active( $post_id ) {
        return get_post_meta( $post_id, '_active', true ) !== 'no';
    }


    /**
     * Reformat the `edit` screen
     */
    function current_screen_edit() {
        ?>
        <script type="text/javascript">
             /* <![CDATA[ */
            jQuery(window).ready(function($){
                var h1 = '<?php _e('Custom Code', 'custom-css-js-pro'); ?> ';
                h1 += '<a href="post-new.php?post_type=custom-css-js&language=css" class="page-title-action"><?php _e('Add CSS Code', 'custom-css-js-pro'); ?></a>';
                h1 += '<a href="post-new.php?post_type=custom-css-js&language=js" class="page-title-action"><?php _e('Add JS Code', 'custom-css-js-pro'); ?></a>';
                h1 += '<a href="post-new.php?post_type=custom-css-js&language=html" class="page-title-action"><?php _e('Add HTML Code', 'custom-css-js-pro'); ?></a>';
                $("#wpbody-content h1").html(h1);
            });

        </script>
        <?php
    }


    /**
     * Reformat the `post` screen
     */
    function current_screen_post() {

        $this->remove_unallowed_metaboxes();

        if ( isset( $_GET['post'] ) ) {
            $action = __('Edit %s code', 'custom-css-js-pro');
            $post_id = esc_attr($_GET['post']);
        } else {
            $action = __('Add %s code', 'custom-css-js-pro');
            $post_id = false;
        }
        $language = $this->get_language($post_id);

        $title = sprintf($action, strtoupper($language) );

        if ( $post_id != false ) {
            $title .= ' <a href="post-new.php?post_type=custom-css-js&language=css" class="page-title-action">'.__('Add CSS Code', 'custom-css-js-pro').'</a> ';
            $title .= '<a href="post-new.php?post_type=custom-css-js&language=js" class="page-title-action">'.__('Add JS Code', 'custom-css-js-pro') .'</a>';
            $title .= '<a href="post-new.php?post_type=custom-css-js&language=html" class="page-title-action">'.__('Add HTML Code', 'custom-css-js-pro').'</a>';
        }

        ?>
        <script type="text/javascript">
             /* <![CDATA[ */
            jQuery(window).ready(function($){
                $("#wpbody-content h1").html('<?php echo $title; ?>');
                $("#message.updated.notice").html('<p><?php _e('Code updated', 'custom-css-js-pro'); ?></p>');
            });
            /* ]]> */
        </script>
        <?php
    }


    /**
     * Remove unallowed metaboxes from custom-css-js edit page
     *
     * Use the custom-css-js-meta-boxes filter to add/remove allowed metaboxdes on the page
     */
    function remove_unallowed_metaboxes() {
        global $wp_meta_boxes;

        // Side boxes
        $allowed = array( 'submitdiv', 'custom-code-options' );

        $allowed = apply_filters( 'custom-css-js-meta-boxes', $allowed );

        foreach( $wp_meta_boxes['custom-css-js']['side'] as $_priority => $_boxes ) {
            foreach( $_boxes as $_key => $_value ) {
            if ( ! in_array( $_key, $allowed ) ) {
                unset( $wp_meta_boxes['custom-css-js']['side'][$_priority][$_key] );
            }
            }
        }


        // Normal boxes
        $allowed = array( 'slugdiv', 'previewdiv', 'url-rules', 'revisionsdiv', 'activatelicensediv' );

        $allowed = apply_filters( 'custom-css-js-meta-boxes-normal', $allowed );

        foreach( $wp_meta_boxes['custom-css-js']['normal'] as $_priority => $_boxes ) {
            foreach( $_boxes as $_key => $_value ) {
            if ( ! in_array( $_key, $allowed ) ) {
                unset( $wp_meta_boxes['custom-css-js']['normal'][$_priority][$_key] );
            }
            }
        }


        unset($wp_meta_boxes['custom-css-js']['advanced']);
    }




    /**
     * Add the codemirror editor in the `post` screen
     */
    public function codemirror_editor( $post ) {
        $current_screen = get_current_screen();

        if ( $current_screen->post_type != 'custom-css-js' ) {
            return false;
        }

        $settings = get_option('ccj_settings');
        $editor_theme = isset($settings['ccj_editor_theme']) ? $settings['ccj_editor_theme'] : 'default';

        if ( empty( $post->title ) && empty( $post->post_content ) ) {
            $new_post = true;
            $post_id = false;
        } else {
            $new_post = false;
            if ( ! isset( $_GET['post'] ) ) $_GET['post'] = $post->id;
            $post_id = esc_attr($_GET['post']);
        }
        $language = $this->get_language($post_id);

        switch ( $language ) {
            case 'js' :
                if ( $new_post ) {
                    $post->post_content = __('/* Add your JavaScript code here.

If you are using the jQuery library, then don\'t forget to wrap your code inside jQuery.ready() as follows:

jQuery(document).ready(function( $ ){
    // Your code in here
});

--

If you want to link a JavaScript file that resides on another server (similar to
<script src="https://example.com/your-js-file.js"></script>), then please use
the "Add HTML Code" page, as this is a HTML code that links a JavaScript file.

End of comment */ ', 'custom-css-js-pro') . PHP_EOL . PHP_EOL;
                }
                $code_mirror_mode = 'text/javascript';
                $code_mirror_before = '<script type="text/javascript">';
                $code_mirror_after = '</script>';
                break;
           case 'html' :
                if ( $new_post ) {
                    $post->post_content = __('<!-- Add HTML code in the header, the footer or in the content as a shortcode.

## In the header
	For example, you can add the following code to the header for loading the jQuery library from Google CDN:
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

	or the following one for loading the Bootstrap library from MaxCDN:
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

## As shortcode
	You can use it in a post/page as [ccj id="shortcode_id"]. ("ccj" stands for "Custom CSS and JS").

## Shortcode variables:
	For the shortcode: [ccj id="shortcode_id" variable="Ana"]

	and the shortcode content: Good morning, {$variable}!
    OR, equivalently, the content: Good morning, <?php echo $variable; ?>

	this will be output on the website: Good morning, Ana!

-- End of the comment --> ', 'custom-css-js-pro') . PHP_EOL . PHP_EOL;

                }
                $code_mirror_mode = 'application/x-httpd-php';
                $code_mirror_before = '';
                $code_mirror_after = '';
                break;

            case 'php' :
                if ( $new_post ) {
                    $post->post_content = __('/* The following will be executed as if it were written in functions.php. */', 'custom-css-js-pro') . PHP_EOL . PHP_EOL;
                }
                $code_mirror_mode = 'php';
                $code_mirror_before = '<?php';
                $code_mirror_after = '?>';

                break;
            default :
                if ( $new_post ) {
                    $post->post_content = __('/* Add your CSS code here.

For example:
.example {
    color: red;
}

For brushing up on your CSS knowledge, check out http://www.w3schools.com/css/css_syntax.asp

End of comment */ ', 'custom-css-js-pro') . PHP_EOL . PHP_EOL;


                }
                $code_mirror_mode = 'text/css';
                $code_mirror_before = '<style type="text/css">';
                $code_mirror_after = '</style>';

        }

            ?>
              <form style="position: relative; margin-top: .5em;">
                <div id="codemirror_all">

                <div class="code-mirror-buttons">
                <div class="button-left" id="ccj-beautifier" ><span rel="tipsy" original-title="<?php _e('Beautify Code', 'custom-css-js-pro'); ?>"><button type="button" tabindex="-1" id="ccj-beautifier"><i class="ccj-i-beautifier"></i></button></span></div>
                <div class="button-right" id="ccj-fullscreen-button" alt="<?php _e('Distraction-free writing mode', 'custom-css-js-pro'); ?>"><span rel="tipsy" original-title="<?php _e('Fullscreen', 'custom-css-js-pro'); ?>"><button role="presentation" type="button" tabindex="-1"><i class="ccj-i-fullscreen"></i></button></span></div>
<input type="hidden" name="fullscreen" id="ccj-fullscreen-hidden" value="false" />
<!-- div class="button-right" id="ccj-search-button" alt="Search"><button role="presentation" type="button" tabindex="-1"><i class="ccj-i-find"></i></button></div -->

                </div>

                <div class="code-mirror-before <?php if (!empty($this->theme) ) echo 'cm-before-' . $this->theme; ?>"><div><?php echo htmlentities( $code_mirror_before );?></div></div>
                <textarea class="wp-editor-area" id="content" mode="<?php echo htmlentities($code_mirror_mode); ?>" name="content"><?php echo $post->post_content; ?></textarea>
                <div class="code-mirror-after <?php if (!empty($this->theme) ) echo 'cm-after-' . $this->theme; ?>"><div><?php echo htmlentities( $code_mirror_after );?></div></div>

                <table id="post-status-info"><tbody><tr>
                    <td class="autosave-info">
                    <span class="autosave-message">&nbsp;</span>
                <?php
                    if ( 'auto-draft' != $post->post_status ) {
                        echo '<span id="last-edit">';
                    if ( $last_user = get_userdata( get_post_meta( $post->ID, '_edit_last', true ) ) ) {
                        printf(__('Last edited by %1$s on %2$s at %3$s', 'custom-css-js-pro'), esc_html( $last_user->display_name ), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
                    } else {
                        printf(__('Last edited on %1$s at %2$s', 'custom-css-js-pro'), mysql2date(get_option('date_format'),      $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
                    }
                    echo '</span>';
                } ?>
                    </td>
                </tr></tbody></table>

                <input type="hidden" id="update-post_<?php echo $post->ID ?>" value="<?php echo wp_create_nonce('update-post_'. $post->ID ); ?>" />
                <input type="hidden" id="editor_theme" name="editor_theme" value="<?php echo $editor_theme; ?>" />

                </div>

              </form>
    <?php

    }


    /**
     * Show the options form in the `post` screen
     */
    function custom_code_options_meta_box_callback( $post ) {


            $options = ccj_get_options( $post->ID );

            $meta = ccj_get_options_meta();

            if ( isset( $_GET['language'] ) ) {
                $options['language'] = $this->get_language();
            }


            wp_nonce_field( 'options_save_meta_box_data', 'custom-css-js_meta_box_nonce' );

            ?>
            <div class="options_meta_box">
            <?php

            $output = '';

            foreach( $meta as $_key => $a ) {

                // Don't show Pre-processors for JavaScript Codes
                if ( $options['language'] == 'js' && $_key == 'preprocessor' ) {
                    continue;
                }

                $output .= '<h3>' . $a['title'] . '</h3>' . PHP_EOL;

                $output .= $this->render_input( $_key, $a, $options);

            }

            echo $output;

            ?>

            <input type="hidden" name="custom_code_language" value="<?php echo $options['language']; ?>" />

            <div style="clear: both;"></div>

            </div>


            <?php
    }


    /**
     * Save the post and the metadata
     */
    function options_save_meta_box_data( $post_id ) {

        // The usual checks
        if ( ! isset( $_POST['custom-css-js_meta_box_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['custom-css-js_meta_box_nonce'], 'options_save_meta_box_data' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( isset( $_POST['post_type'] ) && 'custom-css-js' != $_POST['post_type'] ) {
            return;
        }


        $defaults = ccj_get_options();
        if ( $_POST['custom_code_language'] == 'html' ) {
            $defaults = ccj_get_options( $post_id, 'html');
        }
        $options = array();

        foreach( $defaults as $_field => $_default ) {
            $options[ $_field ] = isset( $_POST['custom_code_'.$_field] ) ? $_POST['custom_code_'.$_field] : $_default;
        }

        if ( $options['language'] === 'html' && $options['type'] === 'shortcode' ) {
            $options['name'] = trim($_POST['custom_code_name']);
        }

        update_post_meta( $post_id, 'options', $options );

        // Save the Custom Code in a file in `wp-content/uploads/custom-css-js`
        if ( $options['language'] != 'html' ) {
            $filename = $post_id . '.' . $options['language'];
            ccj_save_code_file( $_POST['content'], $options, $filename );
        }

        ccj_build_search_tree();

    }



    /**
     * Show the options form in the `post` screen for HTML language
     */
    function custom_code_options_meta_box_callback_html( $post ) {

        $options = ccj_get_options( $post->ID, 'html');

        $meta = ccj_get_options_meta_html();

        if ( isset( $_GET['language'] ) ) {
            $options['language'] = $this->get_language();
        }

        wp_nonce_field( 'options_save_meta_box_data', 'custom-css-js_meta_box_nonce' );

        ?>
        <div class="options_meta_box">
        <?php

        $output = '';

        foreach( $meta as $_key => $a ) {

            $output .= '<h3>' . $a['title'] . '</h3>' . PHP_EOL;

            $output .= $this->render_input( $_key, $a, $options);

        }

        echo $output;

        ?>

        <input type="hidden" name="custom_code_language" value="<?php echo $options['language']; ?>" />

        <div style="clear: both;"></div>

        </div>


        <?php
    }



    /**
     * Create the custom-css-js dir in uploads directory
     *
     * Show a message if the directory is not writable
     *
     * Create an empty index.php file inside
     */
    function create_uploads_directory() {
        $current_screen = get_current_screen();

        // Check if we are editing a custom-css-js post
        if ( $current_screen->base != 'post' || $current_screen->post_type != 'custom-css-js' ) {
            return false;
        }

        $dir = CCJ_UPLOAD_DIR;

        // Create the dir if it doesn't exist
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }

        // Show a message if it couldn't create the dir
        if ( ! file_exists( $dir ) ) : ?>
             <div class="notice notice-error is-dismissible">
             <p><?php esc_html_e('The <b>custom-css-js</b> directory could not be created', 'custom-css-js-pro'); ?></p>
             <p><?php _e('Please run the following commands in order to make the directory', 'custom-css-js-pro') ?>: <br /><strong>mkdir <?php echo $dir; ?>; </strong><br /><strong>chmod 777 <?php echo $dir; ?>;</strong></p>
            </div>
        <?php return; endif;


        // Show a message if the dir is not writable
        if ( ! wp_is_writable( $dir ) ) : ?>
             <div class="notice notice-error is-dismissible">
             <p><?php printf(esc_html__('The <b>%</b> directory is not writable, therefore the CSS and JS files cannot be saved.', 'custom-css-js-pro'), $dir); ?></p>
             <p><?php _e('Please run the following command to make the directory writable', 'custom-css-js-pro'); ?>:<br /><strong>chmod 777 <?php echo $dir; ?> </strong></p>
            </div>
        <?php return; endif;


        // Write a blank index.php
        if ( ! file_exists( $dir. '/index.php' ) ) {
            $content = '<?php' . PHP_EOL . '// Silence is golden.';
            @file_put_contents( $dir. '/index.php', $content );
        }
    }

    function revision_save_meta_box_data( $post_id, $post ) {

        $parent_id = wp_is_post_revision( $post_id );
        if ( ! $parent_id ) {
            return;
        }

        $parent  = get_post( $parent_id );
        if ( $parent->post_type != 'custom-css-js' ) {
            return;
        }

        $options = get_post_meta( $parent->ID, 'options', true );

        if ( false !== $options )
            add_metadata( 'post', $post_id, 'options', $options );

    }

    /**
     * Rebuilt the tree when you trash or restore a custom code
     */
    function trash_post( $post_id ) {
        ccj_build_search_tree();
    }


    /**
     * Compatibility with `shortcoder` plugin
     */
    function compatibility_shortcoder() {
        ob_start( array( $this, 'compatibility_shortcoder_html' ) );
    }
    function compatibility_shortcoder_html( $html ) {
        if ( strpos( $html, 'QTags.addButton' ) === false ) return $html;
        if ( strpos( $html, 'codemirror/codemirror-compressed.js' ) === false ) return $html;

        return str_replace( 'QTags.addButton', '// QTags.addButton', $html );
    }



    /**
     * Initiate the EDD_SL_Plugin_Updater class
     */
    public function edd_updater() {
        if( !class_exists( 'EDD_SL_Plugin_Updater_CCS' ) ) {
            include( 'edd/EDD_SL_Plugin_Updater.php' );
        }

        $a = ccj_plugin_updater_data();
        $a['data']['license'] = trim( get_option( 'ccj_license_key' ) );
        $a['data']['url'] = home_url();
        $a['data']['item_name'] = $a['data']['plugin_name'];

        $edd_updater = new EDD_SL_Plugin_Updater_CCS( $a['plugin_server'], $a['file'], $a['data'] );

    }


    /**
     * Render the checkboxes, radios, selects and inputs
     */
    function render_input( $_key, $a, $options ) {
        $name = 'custom_code_' . $_key;
        $output = '';

        // Show radio type options
        if ( $a['type'] === 'radio' ) {
            $selected = '';
            $output .= '<div class="radio-group">' . PHP_EOL;
            foreach( $a['values'] as $__key => $__value ) {
                $id = $name . '-' . $__key;
                $dashicons = isset($__value['dashicon'] ) ? 'dashicons-before dashicons-' . $__value['dashicon'] : '';
                $selected = ( $__key == $options[$_key] ) ? ' checked="checked" ' : '';
                $output .= '<input type="radio" '. $selected.'value="'.$__key.'" name="'.$name.'" id="'.$id.'">' . PHP_EOL;
                $output .= '<label class="'.$dashicons.'" for="'.$id.'"> '.$__value['title'].'</label><br />' . PHP_EOL;
            }

            if ( $_key === 'type' && $options['type'] === 'shortcode' ) {
                $output .= '<div id="custom_code_name_div"><label for="custom_code_name">'. __('Shortcode id', 'custom-css-js-pro') .': </label> <input type="text" name="custom_code_name" id="custom_code_name" value="'.$options['name'].'" /></div>';
            }

            $output .= '</div>' . PHP_EOL;
        }

        // Show checkbox type options
        if ( $a['type'] == 'checkbox' ) {
            $dashicons = isset($a['dashicon'] ) ? 'dashicons-before dashicons-' . $a['dashicon'] : '';
            $selected = ( isset($options[$_key]) && $options[$_key] == '1') ? ' checked="checked" ' : '';
            $output .= '<div class="radio-group">' . PHP_EOL;
            $output .= '<input type="checkbox" '.$selected.' value="1" name="'.$name.'" id="'.$name.'">' . PHP_EOL;
            $output .= '<label class="'.$dashicons.'" for="'.$name.'"> '.$a['title'].'</label>';
            $output .= '</div>' . PHP_EOL;
        }

        // Show text type options
        if ( $a['type'] == 'text' ) {
            $output .= '<div class="radio-group">' . PHP_EOL;
            $output .= '<input type="text" value="'.$options[$_key].'" name="'.$name.'" id="'.$name.'">' . PHP_EOL;
            $output .= '</div>'. PHP_EOL;

        }


        // Show select type options
        if ( $a['type'] == 'select' ) {
            $output .= '<div class="radio-group">' . PHP_EOL;
            $output .= '<select name="'.$name.'" id="'.$name.'">' . PHP_EOL;
            foreach( $a['values'] as $__key => $__value ) {
                $selected = ( isset($options[$_key]) && $options[$_key] == $__key) ? ' selected="selected"' : '';
                $output .= '<option value="'.$__key.'"'.$selected.'>' . $__value . '</option>' . PHP_EOL;
            }
            $output .= '</select>' . PHP_EOL;
            $output .= '</div>' . PHP_EOL;
        }

        return $output;

    }


    /**
     * Get the language for the current post
     */
    function get_language( $post_id = false ) {
        if( $post_id !== false ) {
            $options = ccj_get_options( $post_id );
            $language = $options['language'];
        } else {
            $language = isset( $_GET['language'] ) ? esc_attr(strtolower($_GET['language'])) : 'css';
        }
        if ( !in_array($language, array('css', 'js', 'html'))) $language = 'css';

        return $language;
    }


    /**
     * Show the activate/deactivate link in the row's action area
     */
    function post_row_actions($actions, $post) {
        if ( 'custom-css-js' !== $post->post_type ) {
            return $actions;
        }

        $url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post->ID), 'ccj-active-code-'. $post->ID );
        if ( $this->is_active( $post->ID) ) {
            $active_title = __('The code is active. Click to deactivate it', 'custom-css-js-pro');
            $active_text = __('Deactivate', 'custom-css-js-pro');
        } else {
            $active_title = __('The code is inactive. Click to activate it', 'custom-css-js-pro');
            $active_text = __('Activate', 'custom-css-js-pro');
        }
        $actions['activate'] = '<a href="' . esc_url( $url ) . '" title="'. $active_title . '" class="ccj_activate_deactivate" data-code-id="'.$post->ID.'">' . $active_text . '</a>';

        return $actions;
    }


    /**
    * Show the activate/deactivate link in admin.
    */
    public function post_submitbox_start() {
        global $post;

        if ( ! is_object( $post ) ) return;

        if ( 'custom-css-js' !== $post->post_type ) return;

        if ( !isset( $_GET['post'] ) ) return;

        $url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ccj_active_code&code_id=' . $post->ID), 'ccj-active-code-'. $post->ID );


        if ( $this->is_active( $post->ID) ) {
            $text = __('Active', 'custom-css-js-pro');
            $action = __('Deactivate', 'custom-css-js-pro');
        } else {
            $text = __('Inactive', 'custom-css-js-pro');
            $action = __('Activate', 'custom-css-js-pro');
        }
        ?>
        <div id="activate-action"><span style="font-weight: bold;"><?php echo $text; ?></span>
        (<a class="ccj_activate_deactivate" data-code-id="<?php echo $post->ID; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo $action ?></a>)
        </div>
    <?php
    }





}

return new CustomCSSandJS_AdminPro();
