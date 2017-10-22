<?php
/**
 * Custom CSS and JS PRO
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * CustomCSSandJS_ShowCodesPro 
 */
class CustomCSSandJS_ShowCodesPro {

    var $first_page = '';
    var $search_tree; 
    var $search_tree_raw; 
    var $search_tree_html; 
    var $shortcodes;
    var $upload_dir;
    var $upload_url;
    var $preview_id;


    function set_value($var, $value ) {
        $this->$var = $value;
    }

    /**
     * Check WordPress conditional tags
     */
    function wp_conditional_tags( $allowed_codes = array(), $rules = array() ) {
        if ( ! isset($rules['wp-conditional'] ) ) return $allowed_codes;

        $add_rules = array();
        $remove_rules = array();
        foreach( $rules['wp-conditional'] as $_rule => $_codes ) {
            $_rule = stripslashes( trim( $_rule ) );
            if ( empty( $_rule ) ) continue;
            if ( stripos( $_rule, 'return' ) === false ) {
                $_rule = 'return(' . $_rule . ');';
            }
            if ( eval( $_rule ) ) {
                $add_rules = array_merge( $add_rules, $_codes );
            } else {
                $remove_rules = array_merge( $remove_rules, $_codes );
            }
        }
        $add_rules = array_diff( $add_rules, $remove_rules);

        $allowed_codes = array_merge( $allowed_codes, $add_rules );
        $allowed_codes = array_diff( $allowed_codes, $remove_rules );

        return $allowed_codes;
    }


    /**
     * Check all the url filters and get the allowed codes for this specific page
     */
    function url_rules( $uri, $rules = array() ) {

        if ( $rules === false ) return false;

        if ( ! $rules || count( $rules ) == 0 ) {
            return array();
        }

        $allowed_codes = array();
        $filters = array(
            'all' => 'array_merge',
            'first-page' => 'array_merge',
            'contains' => 'array_merge',
            'equal-to' => 'array_merge',
            'begins-with' => 'array_merge',
            'ends-by' => 'array_merge',
            'not-contains' => 'array_diff',
            'not-equal-to' => 'array_diff',
        );
        foreach( $filters as $_type => $_action ) {
            if ( isset( $rules[$_type] ) ) {
                if ( $_action == 'array_merge' ) {
                    $allowed_codes = array_merge( $allowed_codes, $this->check_url_rules( $_type, $rules[$_type], $uri) );
                } else {
                    $allowed_codes = array_diff( $allowed_codes, $this->check_url_rules( $_type, $rules[$_type], $uri) );
                }
            }
        }

        return array_unique( $allowed_codes );
    }


    /**
     * Check for one type of rules if this page follows it or now
     */
    function check_url_rules ( $type, $rules, $uri ) {

        if ( $type == 'all' ) return $rules;

        if ( $type == 'first-page' ) {
            $first_page = parse_url( $this->first_page );
            if (!isset($first_page['path'])) {
                return $rules;
            }
            if ( $first_page['path'] == $uri || $first_page['path'] . '/' == $uri ) {
                return $rules;
            }
            return array();
        }


        if ( ! is_array( $rules ) || count( $rules ) == 0 ) return array();

        $all_codes = array();

        foreach( $rules as $_key => $_codes ) {
            $accept = false;
            switch( $type ) {
                case 'contains' :
                case 'not-contains' :
                    if ( strpos( $uri, $_key ) !== false ) $accept = true;
                    break;
                case 'equal-to' :
                case 'not-equal-to' :
                    if ( $uri == $_key ) $accept = true;
                    break;
                case 'begins-with' :
                    if ( strpos( $uri, $_key ) === 0 ) $accept = true;
                    break;
                case 'ends-by' :
                    if ( strpos( strrev( $uri ), strrev( $_key ) ) === 0 ) $accept = true;
                    break;
            }
            if ( $accept ) { 
                $all_codes = array_merge( $all_codes, $_codes );
            }
        }
        return $all_codes;
    }

    /**
     * Filter the codes in the search tree and allow only the ones accepted for this page
     */
    function filter_search_tree( $search_tree = array(), $allowed_codes  = array()) {

        if ( ! is_array( $search_tree ) || count( $search_tree ) == 0 ) {
            return false;
        }

        if ( ! is_int( key( $search_tree ) ) ) {
            $search_tree[5] = $search_tree;
        } 
        ksort( $search_tree );

        // there are no $allowed_codes defined, probably just upgraded from the free to the pro version
        if ( $allowed_codes === false ) {
            return $search_tree;
        }

        // on this particular page there shouldn't any codes be shown, therefore the $allowed_codes is empty
        if ( ! is_array( $allowed_codes ) || count($allowed_codes ) == 0 ) {
            return array();
        }

        foreach( $search_tree as $_priority => $_sub ) {
            foreach( $_sub as $_action => $_codes ) {
                if ( strpos( $_action, 'external' ) !== false ) {
                    foreach( $_codes as $__key => $__code ) {
                        if ( ! in_array( $this->short_filename( $__code ), $allowed_codes ) ) {
                            unset( $_codes[$__key] );
                        }
                    }
                } else {
                    if ( !is_array( $_codes ) ) {
                        $_codes = array();
                    }
                    if ( !is_array( $allowed_codes ) ) {
                        $allowed_codes = array();
                    }
                    $_codes = array_intersect( $_codes, $allowed_codes );
                }
                if ( is_array( $_codes ) && count( $_codes ) > 0 ) {
                    $search_tree[$_priority][$_action] = $_codes;
                } else {
                    unset( $search_tree[$_priority][$_action] );
                }
            }
        }

        return $search_tree;
    } 


    /**
     * Filter the codes in the search tree and allow only the ones accepted for this page
     */
    /*
    function filter_search_tree_html( $search_tree = array(), $allowed_codes  = array()) {

        if ( ! is_array( $search_tree ) || count( $search_tree ) == 0 ) {
            return false;
        }

        if ( ! is_int( key( $search_tree ) ) ) {
            $tmp[5] = $search_tree;
            $search_tree = $tmp;
        } 

        ksort( $search_tree );

        $is_mobile = $this->is_mobile();

        foreach( $search_tree as $_priority => $_sub ) {
            foreach( $_sub as $_action => $_codes ) {
                if ( $is_mobile && strpos( $_action, 'desktop' ) === 0 ) {
                   unset( $search_tree[$_action] ); 
                   continue;
                }
                if ( ! $is_mobile && strpos( $_action, 'mobile' ) === 0 ) {
                   unset( $search_tree[$_action] ); 
                   continue;
                }
                $search_tree[$_priority][$_action] = array_intersect( $_codes, $allowed_codes );
            }
        }

        return $search_tree;
    } 
     */



    /**
     * Replace the $preview_id code with the preview, or add the $preview_id code preview
     */
    function search_tree_for_preview( $search_tree = array(), $preview_id ) {
        
        if ( ! is_array( $search_tree ) || count( $search_tree ) == 0 ) {
            $search_tree = array();
        }

        if ( empty($preview_id) ) {
            return $search_tree;
        }


        $transient = get_transient(CCJ_PREVIEW_PREFIX . $preview_id ); 

        if ( false == $transient ) {
            add_action( 'wp_head', array( $this, 'alert_preview_expired' ) );
        } 


        // Remove the non-preview entry of the code from the search_tree
        $post_meta = get_post_meta($transient['post_ID'], 'options', true );


        if ( $post_meta ) {
            $tree_branch = $post_meta['side'] . '-' .$post_meta['language'] . '-' . $post_meta['type'] . '-' . $post_meta['linking'];

            foreach( $search_tree as $priority => $_sub ) {
                if ( ! isset( $_sub[$tree_branch] ) ) continue;
                foreach( $_sub[$tree_branch] as $_key => $_codes ) {
                    if ( strpos( $_codes, $transient['post_ID'] . '.' ) === 0 || $_codes == $transient['post_ID']) {
                        unset( $search_tree[$priority][$tree_branch][$_key] );
                    }
                }
                if ( count( $_sub[$tree_branch] ) == 0 ) {
                    unset( $search_tree[$priority][$tree_branch] );
                }
            }
        }

        // Add the preview entry of the code in the search_tree
        $new_tree_branch = $transient['side'] . '-' . $transient['language'] . '-' . $transient['type'] . '-' . $transient['linking'];

        $priority = $transient['priority'];

        $filename = $transient['post_ID'] . '-preview.' . $transient['language'];

        if ( $transient['linking'] == 'external' ) {
            $filename .= '?v=' . rand(1, 10000); 
        }

        $search_tree[$priority][$new_tree_branch][] = $filename;

        return $search_tree;
    }

    /**
     * Show an alert when the preview transient is expired
     */
    function alert_preview_expired() {
        $message = __('The preview id you are using is already expired. Please generate the preview again.', 'custom-css-js-pro');
        echo '<script type="text/javascript"> alert("'.$message.'"); </script>' . PHP_EOL;
    }



    /**
     * Add the appropriate wp actions
     */
    function print_code_actions($search_tree = array()) {

        if( ! is_array($search_tree) || count( $search_tree ) == 0 ) {
            return;
        }

        foreach( array( 'wp_head', 'wp_footer', 'admin_head', 'admin_footer', 'login_head', 'login_footer' ) as $action ) {
            add_action( $action, array( $this, 'print_' . $action), 30 );
        }
    }


    /**
     * Print_wp_head, print_wp_footer, print_admin_head, print_admin_footer
     */
    public function __call( $function, $args ) {
        if ( strstr( $function, 'print_' ) == false ) {
            return false;
        }

        $action = str_replace( 'print_', '', $function );

        foreach( $this->search_tree as $_priority => $_sub ) {
            foreach( $_sub as $_where => $_codes ) {

                if ( $action == 'wp_head' ) {
                    if ( strpos( $_where, 'frontend' ) !== false && strpos( $_where, 'header' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

                if ( $action == 'wp_footer' ) {
                    if ( strpos( $_where, 'frontend' ) !== false && strpos( $_where, 'footer' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

                if ( $action == 'admin_head' ) {
                    if ( strpos( $_where, 'admin' ) !== false && strpos( $_where, 'header' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

                if ( $action == 'admin_footer' ) {
                    if ( strpos( $_where, 'admin' ) !== false && strpos( $_where, 'footer' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

                if ( $action == 'login_head' ) {
                    if ( strpos( $_where, 'login' ) !== false && strpos( $_where, 'header' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

                if ( $action == 'login_footer' ) {
                    if ( strpos( $_where, 'login' ) !== false && strpos( $_where, 'footer' ) !== false ) {
                        $this->show_this_subtree( $_where, $_codes );
                    }
                }

            }
        }
    }

    function show_this_subtree( $_where, $_codes ) {
        
        $output = '';

        // print the `internal` code
        if ( strstr( $_where, 'internal' ) ) {

            $before = '<!-- start Simple Custom CSS and JS -->' . PHP_EOL; 
            $after = '<!-- end Simple Custom CSS and JS -->' . PHP_EOL;
            if ( strpos( $_where, 'css' ) !== false ) {
                $before .= '<style type="text/css">' . PHP_EOL;
                $after = '</style>' . PHP_EOL . $after;
            }
            if ( strpos( $_where, 'js' ) !== false ) {
                $before .= '<script type="text/javascript">' . PHP_EOL;
                $after = '</script>' . PHP_EOL . $after;
            }

            foreach( $_codes as $_post_id ) {
                if ( strstr( $_post_id, 'css' ) || strstr( $_post_id, 'js' ) ) {
                    @include_once( $this->upload_dir . '/' . $_post_id );
                } else {
                    $post = get_post( $_post_id );
                    $output .= $before . $post->post_content . $after;
                }
            }            
        }

        // link the `external` code
        if ( strpos( $_where, 'external' ) !== false ) {
            if ( strpos( $_where, 'js' ) !== false ) {
                foreach( $_codes as $_filename ) {
                    $output .= PHP_EOL . "<script type='text/javascript' src='".$this->upload_url . '/' . $_filename."'></script>" . PHP_EOL;
                }
            }

            if ( strpos( $_where, 'css' ) !== false ) {
                foreach( $_codes as $_filename ) {
                    $id = $this->short_filename($_filename) . '-css';
                    $href = $this->upload_url . '/' . $_filename;
                    $output .= PHP_EOL . "<link rel='stylesheet' id='".$id."'  href='".$href."' type='text/css' media='all' />" . PHP_EOL;
                }
            }
        }

        if ( strpos( $_where, 'html' ) !== false ) {
            $is_mobile = $this->is_mobile();
            if ( ( $is_mobile && strpos( $_where, 'desktop' ) === false ) || ( !$is_mobile && strpos( $_where, 'mobile' ) === false ) ) {
                foreach( $_codes as $_post_id ) {
                    if ( strpos( $_post_id, '-preview' ) ) {
                        @include_once( $this->upload_dir . '/' . $_post_id );
                    } else {
                        $post = get_post( $_post_id );
                        $output .= $post->post_content;
                    }
                }
            }
        }

        echo $output;


    }

    /**
     * Print the custom code.
     */
    /*
    public function __call( $function, $args ) {

        if ( strstr( $function, 'print_' ) == false ) {
            return false;
        }

        $function = str_replace( 'print_', '', $function );

        if ( ! isset( $this->search_tree[ $function ] ) ) {
            return false;
        } 

        $args = $this->search_tree[ $function ];

        if ( ! is_array( $args ) || count( $args ) == 0 ) {
            return false;
        }

        $output = '';

        // print the `internal` code
        if ( strstr( $function, 'internal' ) ) {

            $before = '<!-- start Simple Custom CSS and JS -->' . PHP_EOL; 
            $after = '<!-- end Simple Custom CSS and JS -->' . PHP_EOL;
            if ( strpos( $function, 'css' ) !== false ) {
                $before .= '<style type="text/css">' . PHP_EOL;
                $after = '</style>' . PHP_EOL . $after;
            }
            if ( strpos( $function, 'js' ) !== false ) {
                $before .= '<script type="text/javascript">' . PHP_EOL;
                $after = '</script>' . PHP_EOL . $after;
            }

            foreach( $args as $_post_id ) {
                if ( strstr( $_post_id, 'css' ) || strstr( $_post_id, 'js' ) ) {
                    @include_once( $this->upload_dir . '/' . $_post_id );
                } else {
                    $post = get_post( $_post_id );
                    $output .= $before . $post->post_content . $after;
                }
            }            
        }

        // link the `external` code
        if ( strpos( $function, 'external' ) !== false ) {
            if ( strpos( $function, 'js' ) !== false ) {
                foreach( $args as $_filename ) {
                    $output .= PHP_EOL . "<script type='text/javascript' src='".$this->upload_url . '/' . $_filename."'></script>" . PHP_EOL;
                }
            }

            if ( strpos( $function, 'css' ) !== false ) {
                foreach( $args as $_filename ) {
                    $id = $this->short_filename($_filename) . '-css';
                    $href = $this->upload_url . '/' . $_filename;
                    $output .= PHP_EOL . "<link rel='stylesheet' id='".$id."'  href='".$href."' type='text/css' media='all' />" . PHP_EOL;
                }
            }
        }

        echo $output;

        return $output;
    }
     */

    /**
     * Strip the ?v= GET parameter at the end of the filename
     */
    function short_filename($filename) {
        return preg_replace( '@\.(css|js)\?v=.*$@', '.$1', $filename );
    }


    /**
     * Add the appropriate wp actions
     */
    function print_html_code_actions($search_tree = array()) {

        if( ! is_array($search_tree) || count( $search_tree ) == 0 ) {
            return;
        }

        foreach( $search_tree as $_key => $_value ) {
            $action = '';
            $_key = str_replace( array('desktop-', 'mobile-', 'both-'), '', $_key );

            $allowed_hooks = array(
                'wp_head', 'wp_footer' 
            );

            if ( ! in_array( $_key, $allowed_hooks ) ) {
                continue;
            }

            add_action( $action, array( $this, 'printh_' . $_key ) );
        }
    }


    /**
     * Add the shortcodes
     */
    function add_shortcodes() {
        if ( !is_array( $this->search_tree_raw) || count($this->search_tree_raw) == 0 ) return;

        if ( !isset( $this->search_tree_raw[10] ) ) return;

        if ( !isset( $this->search_tree_raw[10]['shortcode'] ) ) return;

        $shortcodes = array();
        foreach( $this->search_tree_raw[10]['shortcode'] as $shortcode_id ) {
            $shortcode = explode('-', $shortcode_id, 2);

            if ( !is_array($shortcode) || count($shortcode) !== 2 )
                continue;

            $shortcodes[$shortcode[1]] = $shortcode[0];
        }
        $this->shortcodes = $shortcodes;

        add_shortcode( 'ccj', array( $this, 'print_shortcode' ) );
    }

    /**
     * Print the shortcode content
     */
    function print_shortcode( $atts ) {
        if ( !isset($atts['id']) || !isset($this->shortcodes[$atts['id']] ) ) return;

        $post = get_post( $this->shortcodes[$atts['id']]);
        $ccj_content_print = $post->post_content;

        if ( count( $atts ) > 1 ) {
            foreach( $atts as $_key => $_value ) {
                $ccj_content_print = str_replace('{$'.$_key.'}', $_value, $ccj_content_print );
            }
        }

        ob_start();
        extract($atts);
        eval('?>' . str_ireplace(array('&lt;?php', '?&gt;'), array('<?php', '?>'), $ccj_content_print ) );
        $ccj_content_print = ob_get_clean();

        $ccj_content_print = do_shortcode( $ccj_content_print );

        return $ccj_content_print;
    }




    /**
     * As in ABS_PATH . WP_INC . '/vars.php';
     *
     * on multi-site installations the vars.php file is loaded later.
     *
     */
    function is_mobile() {
        static $is_mobile = null;

        if ( isset( $is_mobile ) ) {
            return $is_mobile;
        }

        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
                $is_mobile = true;
        } else {
            $is_mobile = false;
        }

        return $is_mobile;
    }


}
