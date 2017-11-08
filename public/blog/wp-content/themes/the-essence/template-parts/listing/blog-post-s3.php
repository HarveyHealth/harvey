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
	$image_size = 'the-essence-s3-4';
	if ( isset( $has_sidebar ) ) {
		// thumbnail width
		if ( ! $has_sidebar ) {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s3-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s3-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s3-4';
			else
				$image_size = 'the-essence-s3-3';
		} else {
			if ( $post_columns == '12' )
				$image_size = 'the-essence-s3-s-12';
			elseif ( $post_columns == '6' )
				$image_size = 'the-essence-s3-s-6';
			elseif ( $post_columns == '4' )
				$image_size = 'the-essence-s3-s-4';
			else
				$image_size = 'the-essence-s3-s-3';
		}
	}

?>
<div <?php post_class( 'blog-post-s3 clearfix ' . $column_class . $post_class_append ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-post-s3-thumb">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
			<div class="blog-post-s3-thumb-cats" data-mtst-selector=".blog-post-s3-thumb-cats" data-mtst-label="Blog Post S3 - Category">
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
			</div><!-- .blog-post-s3-thumb-cats -->
		</div><!-- .blog-post-s3-thumb -->
	<?php endif; ?>

	<div class="blog-post-s3-main">

		<h4 class="blog-post-s3-title" data-mtst-selector=".blog-post-s3-title" data-mtst-label="Blog Post S3 - Title" data-mtst-no-support="border,background"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

		<div class="blog-post-s3-meta">
			<span class="blog-post-s3-meta-author" data-mtst-selector=".blog-post-s3-meta-author a" data-mtst-label="Blog Post S2 - Author" data-mtst-no-support="spacing,background,border">
				<?php the_author_posts_link(); ?>
			</span><!-- .blog-post-s3-meta-author -->
			<span class="blog-post-s3-meta-separator"></span>
			<span class="blog-post-s3-meta-date" data-mtst-selector=".blog-post-s3-meta-date" data-mtst-label="Blog Post S2 - Date" data-mtst-no-support="spacing,background,border">
				<?php
					if ( the_essence_get_theme_mod( 'date_format', 'timeago' ) == 'timeago' ) {
						printf( esc_html__( '%1$s ago', 'the-essence' ), human_time_diff( get_the_time('U'), current_time('timestamp') ) ); 
					} else {
						the_time( get_option( 'date_format' ) );
					}
				?>
			</span><!-- .blog-post-s3-meta-date -->
		</div><!-- .vertica-carousel-item-meta -->

		<?php if ( $show_excerpt ) : ?>
			<div class="blog-post-s3-excerpt" data-mtst-selector=".blog-post-s3-excerpt" data-mtst-label="Blog Post S3 - Excerpt">
				<?php
					if ( defined( 'THE_ESSENCE_TRIMMED_EXCERPT' ) ) {
						the_essence_excerpt( $show_excerpt_length ); 
					} else {
						the_excerpt();
					}
				?>
			</div><!-- .blog-post-s3-excerpt -->
		<?php endif; ?>

	</div><!-- .blog-post-s3-main -->

</div><!-- .blog-post-s3 -->