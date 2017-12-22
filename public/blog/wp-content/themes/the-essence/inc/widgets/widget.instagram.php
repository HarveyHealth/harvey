<?php

add_action( 'widgets_init', create_function( '', 'register_widget( "the_essence_instagram_widget" );' ) );
class The_Essence_Instagram_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'the_essence_instagram_widget', // Base ID
			'MeridianThemes - Instagram Photos', // Name
			array( 'description' => 'Show latest instagram photos.' ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		$amount = $instance['amount'];
		$access_token = $instance['access_token'];

		$username = '';
		if ( $access_token && $access_token !== '' ) {
			$username = explode( '.', $access_token );
			$username = $username[0];
		}

		echo $before_widget;

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		/* Start - Widget Content */



		$transient_id = 'the_essence_instagram_transient_' . $username . '_' . $amount;

		if ( false === ( $images = get_transient( $transient_id ) ) ) {

			$args = array(
				'timeout'     => 30,
			);

			// Get Images
			$url = 'https://api.instagram.com/v1/users/' . $username . '/media/recent/?access_token=' . $access_token . '&count=' . $amount;
			$data = json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $args ) ), true );

			// Check if images are returned
			if ( isset( $data['data'] ) ) {
				
				$images_data = $data['data'];
				$images = array();

				// Generate array
				foreach ( $images_data as $image ) {

					$images[] = array(
						'image' => $image['images']['thumbnail']['url'],
						'url' => $image['link'],
					);

				}

				// Set Trainsient
				set_transient( $transient_id, $images, 12 * HOUR_IN_SECONDS );

			} else {
				$images = false;
			}

		}

		?>

		<div class="instagram-widget-images clearfix">
			<?php if ( $images ) : ?>
				<?php foreach ( $images as $image ) : ?>
					<div class="instagram-widget-image">
						<a href="<?php echo esc_url( $image['url'] ); ?>" target="_blank"><img alt="" src="<?php echo esc_url( $image['image'] ); ?>"/></a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php

		/* End - Widget Content */

		echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['amount'] = strip_tags( $new_instance['amount'] );
		$instance['access_token'] = strip_tags( $new_instance['access_token'] );

		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Get values
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ]; else $title = 'Instagram Photos';
		if ( isset( $instance[ 'amount' ] ) ) $amount = $instance[ 'amount' ]; else $amount = '12';
		if ( isset( $instance[ 'access_token' ] ) ) $access_token = $instance[ 'access_token' ]; else $access_token = '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'amount' ) ); ?>"><?php _e( 'Amount:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'amount' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'amount' ) ); ?>" type="text" value="<?php echo esc_attr( $amount ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php _e( 'Access Token:', 'the-essence' ); ?> ( can be found <a href="http://instagram.pixelunion.net" target="_blank">here</a> )</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>" />
		</p>
		<?php 

	}

}