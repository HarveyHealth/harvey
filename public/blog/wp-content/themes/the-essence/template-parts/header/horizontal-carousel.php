<?php

	// always enabled
	$is_enabled = true;

	// unless single blog post
	if ( is_singular( 'post' ) ) {
		$is_enabled = false;
	}

?>

<?php if ( $is_enabled ) : ?>
	
	<?php

	// query arguments
	$query_args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => 'carousel-small',
			),
		),
	);

	// do the query
	$the_essence_query = new WP_Query( $query_args );

	// Vars
	$count = 0;
	$real_count = 0;
	$post_columns = 3;
	$post_class_append = 'carousel-item ';

	?>

	<?php if ( $the_essence_query->have_posts() ) : ?>

		<div class="carousel-wrapper horizontal-carousel-wrapper">
			
			<div class="wrapper">

				<div class="carousel horizontal-carousel clearfix" data-pagination="true">
				
					<?php while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); $count++; $real_count++; ?>

						<?php include( locate_template( 'template-parts/listing/blog-post-s2.php' ) ); ?>

					<?php endwhile; ?>

				</div><!-- .horizontal-carousel -->

			</div><!-- .wrapper -->

		</div><!-- .horizontal-carousel-wrapper -->

	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
	
<?php endif; ?>