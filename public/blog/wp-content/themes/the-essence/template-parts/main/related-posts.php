<?php
		
	// current post categories
	$current_post_categories = wp_get_post_categories( get_the_ID() );

	// Query args
	$args = array(
		'posts_per_page' => 3,
		'category__in' => $current_post_categories,
		'post__not_in' => array( get_the_ID() ),
		'orderby' => 'rand',
	);	

	// Query posts
	$the_essence_query = new WP_Query( $args );

	// vars ( passed on to template )
	$count = 0;
	$real_count = 0;
	$post_columns = 4;

	// If there are posts
	if ( $the_essence_query->have_posts() ) :

		?>
		<h2 class="section-heading pink"><?php esc_html_e( 'Related Posts', 'the-essence' ); ?></h2>
		<?php

		?><div class="related-posts clearfix"><?php

			// Loop posts
			$has_sidebar = false;
			while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); $count++; $real_count++;

				include( locate_template( 'template-parts/listing/blog-post-s4.php' ) );

			endwhile;

		?></div><!-- .related-posts --><?php

	// Finish if statement
	endif; 

	wp_reset_postdata(); 

?>