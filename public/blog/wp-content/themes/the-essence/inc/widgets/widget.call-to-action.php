<?php

// Register Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "the_essence_call_to_action_widget" );' ) );

// Widget Class
class The_Essence_Call_To_Action_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'the_essence_call_to_action_widget', // Base ID
			esc_html__( 'MeridianThemes - Call To Action', 'the-essence' ), // Name
			array( 'description' => esc_html__( 'Show a call to action banner.', 'the-essence' ) ) // Args
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
		
		// Options
		$bg_image = $instance['bg_image'];
		$heading_primary = $instance['heading_primary'];
		$heading_secondary = $instance['heading_secondary'];
		$button_text = $instance['button_text'];
		$button_url = $instance['button_url'];

		echo $before_widget;

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		/* Start - Widget Content */

		?>

			<div class="call-to-action-widget" <?php if ( $bg_image != '' ) echo 'style="background-image:url(' . $bg_image . ');"'; ?> data-mtst-selector=".call-to-action-widget" data-mtst-label="Call To Action" data-mtst-no-support="typography">

				<div class="call-to-action-widget-inner" data-mtst-selector=".call-to-action-widget-inner" data-mtst-label="Call To Action Inner" data-mtst-no-support="typography">

					<h4 data-mtst-selector=".call-to-action-widget h4" data-mtst-label="Call To Action Title" data-mtst-no-support="background,border"><?php echo esc_html( $heading_primary ); ?></h4>

					<?php if ( $button_url ) : ?>
						<a href="<?php echo esc_url( $button_url ); ?>" class="call-to-action-widget-button"  data-mtst-selector=".call-to-action-widget-button" data-mtst-label="Call To Action Button"><?php echo $button_text; ?></a>
					<?php endif; ?>

					<?php if ( $heading_secondary ) : ?>
						<h5 data-mtst-selector=".call-to-action-widget h5" data-mtst-label="Call To Action Subtitle" data-mtst-no-support="background,border"><?php echo esc_html( $heading_secondary ); ?></h5>
					<?php endif; ?>

				</div><!-- .call-to-action-widget-inner -->

			</div><!-- .call-to-action-widget -->

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
			
		$instance['bg_image'] = strip_tags( $new_instance['bg_image'] );
		$instance['heading_primary'] = strip_tags( $new_instance['heading_primary'] );
		$instance['heading_secondary'] = strip_tags( $new_instance['heading_secondary'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['button_url'] = strip_tags( $new_instance['button_url'] );

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
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ]; else $title = 'Title';
		if ( isset( $instance[ 'bg_image' ] ) ) $bg_image = $instance[ 'bg_image' ]; else $bg_image = '';
		if ( isset( $instance[ 'heading_primary' ] ) ) $heading_primary = $instance[ 'heading_primary' ]; else $heading_primary = 'Description';
		if ( isset( $instance[ 'heading_secondary' ] ) ) $heading_secondary = $instance[ 'heading_secondary' ]; else $heading_secondary = 'Secondary description';
		if ( isset( $instance[ 'button_text' ] ) ) $button_text = $instance[ 'button_text' ]; else $button_text = 'Button Text';
		if ( isset( $instance[ 'button_url' ] ) ) $button_url = $instance[ 'button_url' ]; else $button_url = '';
		

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bg_image' ) ); ?>"><?php esc_html_e( 'BG Image URL:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'bg_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bg_image' ) ); ?>" type="text" value="<?php echo esc_attr( $bg_image ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'heading_primary' ) ); ?>"><?php esc_html_e( 'Primary Text:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading_primary' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading_primary' ) ); ?>" type="text" value="<?php echo esc_attr( $heading_primary ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'heading_secondary' ) ); ?>"><?php esc_html_e( 'Secondary Text:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading_secondary' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading_secondary' ) ); ?>" type="text" value="<?php echo esc_attr( $heading_secondary ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>"><?php esc_html_e( 'Button URL:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_url' ) ); ?>" type="text" value="<?php echo esc_attr( $button_url ); ?>" />
		</p>

		<?php 

	}

}