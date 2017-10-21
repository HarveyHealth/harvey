<?php
	
	// default real count
	if ( ! isset( $real_count ) ) 
		$real_count = 0;

	// default count
	if ( ! isset( $count ) )
		$count = '';

	// default post class append
	if ( ! isset( $post_class_append ) )
		$post_class_append = '';

	// default columns
	if ( ! isset( $post_columns ) ) {
		$post_columns = 12;
	}

	// default excerpt
	if ( ! isset( $show_excerpt ) ) {
		$show_excerpt = false;
	}

	// default excerpt lenth
	if ( ! isset( $show_excerpt_length ) ) {
		$show_excerpt_length = 130;
	}

	// column class
	$column_class = 'col col-' . $post_columns . ' ';
	$count_max = 12 / $post_columns;
	if ( $count == 1 ) {
		$column_class .= 'col-first ';
	}
	if ( $count >= $count_max ) {
		$count = 0;
		$column_class .= 'col-last ';
	}

	// thumbnail width
	$image_size = 'the-essence-s7-4';
	if ( isset( $has_sidebar ) ) {
		// thumbnail width
		if ( ! $has_sidebar ) {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s7-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s7-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s7-4';
			else
				$image_size = 'the-essence-s7-3';
		} else {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s7-s-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s7-s-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s7-s-4';
			else
				$image_size = 'the-essence-s7-s-3';
		}
	}

?>
<div <?php post_class( 'blog-post-s7 clearfix ' . $column_class . $post_class_append ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-post-s7-thumb" data-mtst-selector=".blog-post-s7-thumb" data-mtst-label="Blog Post S7 - Thumbnail" data-mtst-no-support="typography">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
			<div class="blog-post-s7-thumb-cats" data-mtst-selector=".blog-post-s7-thumb-cats" data-mtst-label="Blog Post S7 - Category">
				<?php 
					if ( the_essence_get_theme_mod( 'show_one_cat_in_listings', 'disabled' ) == 'enabled' ) {
						$cats = get_the_category( get_the_ID() );
						if ( is_array( $cats ) ) {
							?><a href="<?php echo get_term_link( $cats[0], 'category' ) ?>"><?php echo esc_html( $cats[0]->cat_name ); ?></a><?php
						}
					} else {
						the_category( ' ' );
					}
				?>
			</div><!-- .blog-post-s7-thumb-cats -->
		</div><!-- .blog-post-s7-thumb -->

	<?php endif; ?>

	<div class="blog-post-s7-number" data-mtst-selector=".blog-post-s7-number" data-mtst-label="Blog Post S7 - Number" data-mtst-no-support="background,border">
		
		<?php 
			
			$count_increment = 0;

			if ( is_page_template( 'template-home.php' ) ) {
			
				if ( is_numeric( get_query_var( 'page' ) ) ) { $paged = get_query_var( 'page' ); } elseif ( is_numeric( get_query_var( 'paged' ) ) ) { $paged = get_query_var( 'paged' ); } else { $paged = 1; }
				if ( $paged < 1 ) { $paged = 1; }

				$posts_per_page = the_essence_get_post_meta( get_queried_object_id(), 'query_posts_per_page' );
				$count_increment = ( $paged - 1 ) * $posts_per_page;

			}

		?>

		<?php if ( ( $real_count + $count_increment ) < 10 ) echo '0'; ?><?php echo $real_count + $count_increment; ?>
	</div><!-- .blog-post-s7-number -->

	<div class="blog-post-s7-main">
		<div class="blog-post-s7-title" data-mtst-selector=".blog-post-s7-title" data-mtst-label="Blog Post S7 - Title" data-mtst-no-support="border,background"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		<div class="blog-post-s7-author" data-mtst-selector=".blog-post-s7-author" data-mtst-label="Blog Post S7 - Author" data-mtst-no-support="border,background"><?php esc_html_e( 'by', 'the-essence' ); ?> <?php the_author_posts_link(); ?></div>
		<div class="blog-post-s7-date" data-mtst-selector=".blog-post-s7-date" data-mtst-label="Blog Post S7 - Date" data-mtst-no-support="border,background"><?php 
			if ( the_essence_get_theme_mod( 'date_format', 'timeago' ) == 'timeago' ) {
				printf( esc_html__( '%1$s ago', 'the-essence' ), human_time_diff( get_the_time('U'), current_time('timestamp') ) ); 
			} else {
				the_time( get_option( 'date_format' ) );
			}
		?></div>
		<?php if ( $show_excerpt ) : ?>
			<div class="blog-post-s7-excerpt" data-mtst-selector=".blog-post-s7-excerpt" data-mtst-label="Blog Post S7 - Excerpt">
				<?php
					if ( defined( 'THE_ESSENCE_TRIMMED_EXCERPT' ) ) {
						the_essence_excerpt( $show_excerpt_length ); 
					} else {
						the_excerpt();
					}
				?>
			</div><!-- .blog-post-s7-excerpt -->
		<?php endif; ?>
		<div class="blog-post-s7-read-more">
			<a data-mtst-selector=".blog-post-s7-read-more a" data-mtst-label="Blog Post S7 - Button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'CONTINUE READING', 'the-essence' ); ?></a>
		</div><!-- .blog-post-read-more -->
	</div><!-- .blog-post-s7-main -->



</div><!-- .blog-post-s7 -->