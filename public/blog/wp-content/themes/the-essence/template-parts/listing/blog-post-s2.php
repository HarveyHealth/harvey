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
<div <?php post_class( 'blog-post-s2 clearfix ' . $column_class . $post_class_append ); ?>>


	<?php if ( has_post_thumbnail() ) : ?>
		<div class="blog-post-s2-thumb">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'the-essence-s2' ); ?></a>
		</div><!-- .blog-post-s2-thumb -->
	<?php endif; ?>

	<div class="blog-post-s2-title">
		<h4 data-mtst-selector=".blog-post-s2-title h4" data-mtst-label="Blog Post S2 - Title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	</div><!-- .blog-post-s2-title -->

</div><!-- .blog-post-s2 -->