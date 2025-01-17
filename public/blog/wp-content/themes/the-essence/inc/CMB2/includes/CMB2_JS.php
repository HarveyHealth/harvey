<?php
/**
 * Handles the dependencies and enqueueing of the CMB2 JS scripts
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */
class CMB2_JS {

	/**
	 * The CMB2 JS handle
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $handle = 'cmb2-scripts';

	/**
	 * The CMB2 JS variable name
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $js_variable = 'cmb2_l10';

	/**
	 * Array of CMB2 JS dependencies
	 * @var   array
	 * @since 2.0.7
	 */
	protected static $dependencies = array( 'jquery' => 'jquery' );

	/**
	 * Add a dependency to the array of CMB2 JS dependencies
	 * @since 2.0.7
	 * @param array|string  $dependencies Array (or string) of dependencies to add
	 */
	public static function add_dependencies( $dependencies ) {
		foreach ( (array) $dependencies as $dependency ) {
			self::$dependencies[ $dependency ] = $dependency;
		}
	}

	/**
	 * Enqueue the CMB2 JS
	 * @since  2.0.7
	 */
	public static function enqueue() {
		// Filter required script dependencies
		$dependencies = apply_filters( 'cmb2_script_dependencies', self::$dependencies );

		// if colorpicker
		if ( ! is_admin() && isset( $dependencies['wp-color-picker'] ) ) {
			self::colorpicker_frontend();
		}

		// if file/file_list
		if ( isset( $dependencies['media-editor'] ) ) {
			wp_enqueue_media();
		}

		// if timepicker
		if ( isset( $dependencies['jquery-ui-datetimepicker'] ) ) {
			wp_register_script( 'jquery-ui-datetimepicker', cmb2_utils()->url( 'js/jquery-ui-timepicker-addon.min.js' ), array( 'jquery-ui-slider' ), CMB2_VERSION );
		}

		// Only use minified files if SCRIPT_DEBUG is off
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$min   = $debug ? '' : '.min';

		// Register cmb JS
		wp_enqueue_script( self::$handle, cmb2_utils()->url( "js/cmb2{$min}.js" ), $dependencies, CMB2_VERSION, true );

		self::localize( $debug );
	}

	/**
	 * We need to register colorpicker on the front-end
	 * @since  2.0.7
	 */
	protected static function colorpicker_frontend() {
		wp_register_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), CMB2_VERSION );
		wp_register_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), CMB2_VERSION );
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
			'clear'         => __( 'Clear', 'the-essence' ),
			'defaultString' => __( 'Default', 'the-essence' ),
			'pick'          => __( 'Select Color', 'the-essence' ),
			'current'       => __( 'Current Color', 'the-essence' ),
		) );
	}

	/**
	 * Localize the php variables for CMB2 JS
	 * @since  2.0.7
	 */
	protected static function localize( $debug ) {
		$l10n = array(
			'ajax_nonce'       => wp_create_nonce( 'ajax_nonce' ),
			'ajaxurl'          => admin_url( '/admin-ajax.php' ),
			'script_debug'     => $debug,
			'up_arrow_class'   => 'dashicons dashicons-arrow-up-alt2',
			'down_arrow_class' => 'dashicons dashicons-arrow-down-alt2',
			'defaults'         => array(
				'color_picker' => false,
				'date_picker'  => array(
					'changeMonth'     => true,
					'changeYear'      => true,
					'dateFormat'      => _x( 'mm/dd/yy', 'Valid formatDate string for jquery-ui datepicker', 'the-essence' ),
					'dayNames'        => explode( ',', __( 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', 'the-essence' ) ),
					'dayNamesMin'     => explode( ',', __( 'Su, Mo, Tu, We, Th, Fr, Sa', 'the-essence' ) ),
					'dayNamesShort'   => explode( ',', __( 'Sun, Mon, Tue, Wed, Thu, Fri, Sat', 'the-essence' ) ),
					'monthNames'      => explode( ',', __( 'January, February, March, April, May, June, July, August, September, October, November, December', 'the-essence' ) ),
					'monthNamesShort' => explode( ',', __( 'Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec', 'the-essence' ) ),
					'nextText'        => __( 'Next', 'the-essence' ),
					'prevText'        => __( 'Prev', 'the-essence' ),
					'currentText'     => __( 'Today', 'the-essence' ),
					'closeText'       => __( 'Done', 'the-essence' ),
					'clearText'       => __( 'Clear', 'the-essence' ),
				),
				'time_picker'  => array(
					'timeOnlyTitle' => __( 'Choose Time', 'the-essence' ),
					'timeText'      => __( 'Time', 'the-essence' ),
					'hourText'      => __( 'Hour', 'the-essence' ),
					'minuteText'    => __( 'Minute', 'the-essence' ),
					'secondText'    => __( 'Second', 'the-essence' ),
					'currentText'   => __( 'Now', 'the-essence' ),
					'closeText'     => __( 'Done', 'the-essence' ),
					'timeFormat'    => _x( 'hh:mm TT', 'Valid formatting string, as per http://trentrichardson.com/examples/timepicker/', 'the-essence' ),
					'controlType'   => 'select',
					'stepMinute'    => 5,
				),
			),
			'strings' => array(
				'upload_file'  => __( 'Use this file', 'the-essence' ),
				'upload_files' => __( 'Use these files', 'the-essence' ),
				'remove_image' => __( 'Remove Image', 'the-essence' ),
				'remove_file'  => __( 'Remove', 'the-essence' ),
				'file'         => __( 'File:', 'the-essence' ),
				'download'     => __( 'Download', 'the-essence' ),
				'check_toggle' => __( 'Select / Deselect All', 'the-essence' ),
			),
		);

		wp_localize_script( self::$handle, self::$js_variable, apply_filters( 'cmb2_localized_data', $l10n ) );
	}

}
