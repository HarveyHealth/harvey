<?php
/**
 * Custom CSS and JS
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * CustomCSSandJS_URLRules 
 */
class CustomCSSandJS_URLRules {

    /**
     * Constructor
     */
    public function __construct() {
        
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
    }

    /**
     * Return the URL Rules filters
     */
    function get_filters() {
        return $filters = array(
            'all'           => __('All Website', 'custom-css-js-pro'),
            'first-page'    => __('First Page', 'custom-css-js-pro'),
            'contains'      => __('URL contains ..', 'custom-css-js-pro'),
            'not-contains'  => __('URL not contains ..', 'custom-css-js-pro'),
            'equal-to'      => __('URL is equal to ..', 'custom-css-js-pro'),
            'not-equal-to'  => __('URL not equal to ..', 'custom-css-js-pro'),
            'begins-with'   => __('URL starts with ..', 'custom-css-js-pro'),
            'ends-by'       => __('URL ends by ..', 'custom-css-js-pro'),
            'wp-conditional'=> __('WP Conditional Tag ..', 'custom-css-js-pro'),
        );
    }

    /**
     * Add the URL Rules metabox
     */
    function add_meta_boxes() {
        
        add_meta_box( 'url-rules', __('Apply only on these Pages', 'custom-css-js-pro') . ccj_a_doc('https://www.silkypress.com/simple-custom-css-js-pro-documentation/#doc-url-rules'), array( $this, 'url_rules_meta_box_callback' ), 'custom-css-js', 'normal' );
    }

    /**
     * Show the URL Rules metabox
     */
    function url_rules_meta_box_callback( $post ) {

        $filters_html = '';
        $filters = $this->get_filters();
        foreach( $filters as $_key => $_value ) {
            $filters_html .= '<option value="'.$_key.'">' . $_value . '</option>';
        }

        $applied_filters = get_post_meta( $post->ID, 'urls', true );
        if ( ! $applied_filters ) {
            $applied_filters = '[{"value":"","type":"all","index":1}]';
        } else {
            $applied_filters = json_decode($applied_filters );
            foreach( $applied_filters as $_key => $_filter ) {
                $applied_filters[$_key]->value = str_replace('"', '\'', $_filter->value); 
                $applied_filters[$_key]->value = htmlspecialchars($_filter->value, ENT_QUOTES);
            }
            $applied_filters = json_encode($applied_filters);
        }

?>
        <input type="hidden" name="scan_anchor_filters" id="wplnst-scan-anchor-filters" value='<?php echo $applied_filters; ?>' />
    <table id="wplnst-elist-anchor-filters" class="wplnst-elist" cellspacing="0" cellpadding="0" border="0" data-editable="true" data-label=""></table>
        <select id="wplnst-af-new-type"><?php echo $filters_html ?></select>&nbsp;
        <input id="wplnst-af-new" type="text" class="regular-text" value="" style="display: none;" />&nbsp;
        <input class="button button-primary" type="button" id="wplnst-af-new-add" value="<?php _e('Add Rule', 'custom-css-js-pro'); ?>" /></td>

<?php
    }



    /**
     * Save the post and the metadata
     */
    function save_meta_box_data( $post_id ) {
        
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

        update_post_meta( $post_id, 'urls', $_POST['scan_anchor_filters'] );

        $this->build_url_tree();
    }


    /**
     * Build a tree where you can quickly find the urls on which the code is active 
     */
    private function build_url_tree() {

        $posts = query_posts( 'post_type=custom-css-js&post_status=publish&nopaging=true' );

        if ( ! is_array( $posts ) || count( $posts ) == 0 ) {
            return false;
        }

        $filters = $this->get_filters();

        $tree = array();
        foreach ( $posts as $_post ) {
            $rules = get_post_meta( $_post->ID, 'urls', true );
            if ( ! $rules ) {
                $rules = '[{"value":"","type":"all","index":1}]';
            }
            $rules = $this->check_array_json( $rules );

            $options = ccj_get_options( $_post->ID );
            if ( ! isset( $options['language'] ) ) continue;
            if ( $options['language'] ==  'html' ) {
                $file = $_post->ID; 
            } else {
                $file = $_post->ID . '.' . $options['language'];
            }

            foreach( $rules as $_key => $_rule ) {
                if ( empty( $_rule['value' ] ) ) {
                    $tree[$_rule['type'] ][] = $file;
                } else {
                    $tree[$_rule['type']][$_rule['value']][] = $file;
                }
            }
        }

        update_option( 'custom-css-js-urls', $tree );
    }

   /**
   * Check a json array value
   */
    public function check_array_json($array, $default = array()) {
        $value = @json_decode($array, true); 
        return (empty($value) || !is_array($value))? $default : $value;
    }





}

return new CustomCSSandJS_URLRules();
