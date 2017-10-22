<?php

class CustomCSSandJS_LicenseForm {

    var $data = array();

    function __construct( $data = array() ) {
        $data['nonce'] = $data['license'] . '_nonce';
        $data['activate'] = $data['license'] . '_activate';
        $data['deactivate'] = $data['license'] . '_deactivate';
        $this->data = $data;
        

        if( !class_exists( 'EDD_SL_Plugin_Updater_CCS' ) ) {
            include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
        }
    }


    /**
     * License form 
     */
    function license_page() {
        $license 	= get_option( $this->data['license_key'] );
        $status 	= get_option( $this->data['license_status'] );
        ?>
            <form method="post">

                <?php settings_fields( $this->data['license'] ); ?>

                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('License Key', 'custom-css-js-pro'); ?>
                            </th>
                            <td>
                                <input id="<?php echo $this->data['license_key']; ?>" name="<?php echo $this->data['license_key']; ?>" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                                <label class="description" for="<?php echo $this->data['license_key']; ?>"><?php _e('Enter your license key', 'custom-css-js-pro'); ?></label>
                            </td>
                        </tr>
                        <?php if( false !== $license ) { ?>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Activate License', 'custom-css-js-pro'); ?>
                                </th>
                                <td>
                                    <?php if( $status !== false && $status == 'valid' ) { ?>
                                        <span style="color:green;"><?php _e('active'); ?></span>
                                        <?php wp_nonce_field( $this->data['nonce'], $this->data['nonce'] ); ?>
                                        <input type="submit" class="button-secondary" name="<?php echo $this->data['deactivate']; ?>" value="<?php _e('Deactivate License', 'custom-css-js-pro'); ?>"/>
                                    <?php } else {
                                        wp_nonce_field( $this->data['nonce'], $this->data['nonce'] ); ?>
                                        <input type="submit" class="button-secondary" name="<?php echo $this->data['activate']; ?>" value="<?php _e('Activate License', 'custom-css-js-pro'); ?>"/>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php submit_button(); ?>

            </form>
        <?php
    }

    function register_option() {
        register_setting($this->data['license'], $this->data['license_key'], array( $this, 'edd_sanitize_license') );
    }

    function edd_sanitize_license( $new ) {
        $old = get_option( $this->data['license_key'] );
        if( $old && $old != $new ) {
            delete_option( $this->data['license_status'] ); // new license has been entered, so must reactivate
        }
        return $new;
    }

    /**
     * Activate/deactivate the license
     */
    function activate_deactivate_license() {

        update_option( $this->data['license_key'], $_POST[$this->data['license_key']] ); 

        if( ! isset( $_POST[$this->data['activate']] ) && ! isset( $_POST[$this->data['deactivate']] ) ) {
            return;
        }


        $edd_action = 'activate_license';
        if ( isset( $_POST[$this->data['deactivate']] ) ) {
            $edd_action = 'deactivate_license';
        }


        if( ! check_admin_referer( $this->data['nonce'], $this->data['nonce'] ) )
            return; 


        $api_params = array(
            'edd_action'=> $edd_action,
            'license' 	=> trim( $_POST[$this->data['license_key']] ),
            'item_name' => urlencode( $this->data['item_name']), 
            'url'       => home_url()
        );

        $response = wp_remote_post( $this->data['store_url'], array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        if ( is_wp_error( $response ) )
            return false;

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( $edd_action == 'activate_license' ) {
            update_option( $this->data['license_status'], $license_data->license );
        } elseif ( $license_data->license == 'deactivated' ) {
            delete_option( $this->data['license_status'] );
        }

    }

}
