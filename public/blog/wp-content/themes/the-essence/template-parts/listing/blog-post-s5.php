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

	$width = 272;
	$mobile_width = 480;
	$height = $width / 1.7;
	$mobile_height = $mobile_width / 1.7;

?>
<?php
		
	// default post class append
	if ( ! isset( $post_class_append ) )
		$post_class_append = '';

?>

<div <?php post_class( 'blog-post-s5 ' . $column_class . $post_class_append ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-post-s5-thumb" data-mtst-selector=".blog-post-s5-thumb" data-mtst-label="Blog Post S5 - Thumbnail" data-mtst-no-support="typography">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'the-essence-s5' ); ?></a>
		</div><!-- .blog-post-s5-thumb -->
	<?php endif; ?>

	<div class="blog-post-s5-title">
		<h2 data-mtst-selector=".blog-post-s5-title h2" data-mtst-label="Blog Post S5 - Title" data-mtst-no-support="background,border"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</div><!-- .blog-post-s5-title -->

	<div class="blog-post-s5-meta">

		<div class="blog-post-s5-meta-author" data-mtst-selector=".blog-post-s5-meta-author" data-mtst-label="Blog Post S5 - Author" data-mtst-no-support="background,border">
			<?php the_author_posts_link(); ?>
		</div><!-- .blog-post-s5-meta-author -->

		<div class="blog-post-s5-meta-date" data-mtst-selector=".blog-post-s5-meta-date" data-mtst-label="Blog Post S5 - Date" data-mtst-no-support="background,border">
			<?php
				if ( the_essence_get_theme_mod( 'date_format', 'timeago' ) == 'timeago' ) {
					printf( esc_html__( '%1$s ago', 'the-essence' ), human_time_diff( get_the_time('U'), current_time('timestamp') ) ); 
				} else {
					the_time( get_option( 'date_format' ) );
				}
			?>
		</div><!-- .blog-post-s5-meta-date -->

	</div><!-- .blog-post-s5-meta -->

</div><!-- .blog-post-s5 -->