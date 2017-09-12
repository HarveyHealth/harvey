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

?>
<div <?php post_class( 'blog-post-s6 clearfix ' . $column_class . $post_class_append ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-post-s6-thumb">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'the-essence-small' ); ?></a>
		</div><!-- .blog-post-s6-thumb -->
	<?php endif; ?>

	<div class="blog-post-s6-main">

		<div class="blog-post-s6-title" data-mtst-selector=".blog-post-s6-title" data-mtst-label="Blog Post S6 - Title" data-mtst-no-support="background,border"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		<div class="blog-post-s6-comments" data-mtst-selector=".blog-post-s6-comments" data-mtst-label="Blog Post S6 - Title" data-mtst-no-support="background,border"><span class="fa fa-commenting-o"></span><?php comments_number( esc_html__( 'No comments', 'the-essence' ), esc_html__( 'One comment', 'the-essence' ), esc_html__( '% comments', 'the-essence' ) ); ?></div>

	</div><!-- .blog-post-s6-main -->

</div><!-- .blog-post-s6-post -->