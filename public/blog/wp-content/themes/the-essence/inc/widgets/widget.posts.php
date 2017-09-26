<?php

add_action( 'widgets_init', create_function( '', 'register_widget( "the_essence_posts_list_widget" );' ) );
class The_Essence_Posts_List_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'the_essence_posts_list_widget', // Base ID
			esc_html__( 'MeridianThemes - Posts List', 'the-essence' ), // Name
			array( 'description' => esc_html__( 'Show recent or popular posts.', 'the-essence' ) ) // Args
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
		$type = $instance['type'];

		// post type
		$post_type = 'post';
		if ( isset( $instance['post_type'] ) ) {
			$post_type = $instance['post_type'];
		} 

		// order
		if ( isset( $instance['order'] ) ) {
			$order = $instance['order'];
		}

		// categories
		$categories = false;
		if ( isset( $instance['categories'] ) ) {
			$categories = $instance['categories'];
		}

		// tags
		$tags = false;
		if ( isset( $instance['tags'] ) ) {
			$tags = $instance['tags'];
		} 

		// Order by
		$orderby = 'date';
		if ( $type == 'popular' ) {
			$orderby = 'comment_count';
		} elseif ( $type == 'recent' ) {
			$orderby = 'date';
		} else {
			$orderby = $type;
		}

		// Order
		if ( ! isset( $order ) ) {
			$order = 'DESC';
		}

		echo $before_widget;

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		/* Start - Widget Content */

		// General args
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => $amount,
			'orderby' => $orderby,
			'order' => $order,
		);

		// categories
		if ( $categories ) {

			// default taxonomy
			$taxonomy = 'category';

			// if not post let's find the taxonomy
			if ( $post_type != 'post' ) {
				$taxonomies = get_object_taxonomies( $post_type, 'objects' );
				foreach ( $taxonomies as $t_slug => $t_data ) {
					if ( $t_data->hierarchical == '1' && $t_data->public == '1' ) {
						$taxonomy = $t_slug;
					}
				}
			}

			$categories = explode( ',', str_replace( ' ', '', $categories ) );
			$args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $categories,
			);

		}

		// tags
		if ( $tags ) {

			// default taxonomy
			$taxonomy = 'post_tag';

			// if not post let's find the taxonomy
			if ( $post_type != 'post' ) {
				$taxonomies = get_object_taxonomies( $post_type, 'objects' );
				foreach ( $taxonomies as $t_slug => $t_data ) {
					if ( $t_data->hierarchical != '1' && $t_data->public == '1' && $t_data->show_ui == '1' ) {
						$taxonomy = $t_slug;
					}
				}
			}

			$tags = explode( ',', str_replace( ' ', '', $tags ) );
			$args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $tags,
			);

		}

		$the_essence_query = new WP_Query( $args );

		if ( $the_essence_query->have_posts() ) :

			?>

			<div class="posts-list-widget clearfix">

				<?php while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); ?>

					<?php include( locate_template( 'template-parts/listing/blog-post-s6.php' ) ); ?>

				<?php endwhile; ?>

			</div><!-- .posts-list-widget -->

			<?php

		endif;

		wp_reset_postdata();

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
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		$instance['amount'] = strip_tags( $new_instance['amount'] );
		$instance['type'] = strip_tags( $new_instance['type'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['categories'] = strip_tags( $new_instance['categories'] );
		$instance['tags'] = strip_tags( $new_instance['tags'] );

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
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ]; else $title = 'Recent Posts';
		if ( isset( $instance[ 'post_type' ] ) ) $post_type = $instance[ 'post_type' ]; else $post_type = 'post';
		if ( isset( $instance[ 'amount' ] ) ) $amount = $instance[ 'amount' ]; else $amount = '4';
		if ( isset( $instance[ 'type' ] ) ) $type = $instance[ 'type' ]; else $type = 'recent';
		if ( isset( $instance[ 'order' ] ) ) $order = $instance[ 'order' ]; else $order = 'DESC';
		if ( isset( $instance[ 'categories' ] ) ) $categories = $instance[ 'categories' ]; else $categories = '';
		if ( isset( $instance[ 'tags' ] ) ) $tags = $instance[ 'tags' ]; else $tags = '';

		// post types
		$post_types = get_post_types( array( 'public' => true ) );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php esc_html_e( 'Post Type:', 'the-essence' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<?php foreach ( $post_types as $pt_value => $pt_label ) : ?>
					<option <?php if ( $post_type == $pt_value ) echo 'selected="selected"'; ?> value="<?php echo esc_attr( $pt_value ); ?>"><?php echo esc_html( $pt_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'amount' ) ); ?>"><?php esc_html_e( 'Amount:', 'the-essence' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'amount' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'amount' ) ); ?>" type="text" value="<?php echo esc_attr( $amount ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Order By:', 'the-essence' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
				<option <?php if ( $type == 'recent' ) echo 'selected="selected"'; ?> value="recent"><?php esc_html_e( 'Date', 'the-essence' ); ?></option>
				<option <?php if ( $type == 'popular' ) echo 'selected="selected"'; ?> value="popular"><?php esc_html_e( 'Comments Count', 'the-essence' ); ?></option>
				<option <?php if ( $type == 'title' ) echo 'selected="selected"'; ?> value="title"><?php esc_html_e( 'Title ( alphabetically )', 'the-essence' ); ?></option>
				<option <?php if ( $type == 'rand' ) echo 'selected="selected"'; ?> value="rand"><?php esc_html_e( 'Random', 'the-essence' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'the-essence' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option <?php if ( $order == 'DESC' ) echo 'selected="selected"'; ?> value="DESC"><?php esc_html_e( 'Descending', 'the-essence' ); ?></option>
				<option <?php if ( $order == 'ASC' ) echo 'selected="selected"'; ?> value="ASC"><?php esc_html_e( 'Ascending', 'the-essence' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>"><?php esc_html_e( 'Categories:', 'the-essence' ); ?></label><br>
			<small><?php esc_html_e( 'Enter the IDs of the categories separated by a comma ( example: 1,2,3,4 )', 'the-essence' ); ?></small>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'categories' ) ); ?>"><?php echo esc_attr( $categories ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php esc_html_e( 'Tags:', 'the-essence' ); ?></label><br>
			<small><?php esc_html_e( 'Enter the IDs of the tags separated by a comma ( example: 1,2,3,4 )', 'the-essence' ); ?></small>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>"><?php echo esc_attr( $tags ); ?></textarea>
		</p>
		<?php 

	}

}
