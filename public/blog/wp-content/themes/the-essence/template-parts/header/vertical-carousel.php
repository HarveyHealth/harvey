<?php

	$is_home = false;

	// if the regular homepage
	if ( is_home() ) {
		$is_home = true;
	}

	// if homepage template
	if ( strpos( get_page_template_slug( get_the_ID() ), 'template-home' ) !== false ) {
		$is_home = true;
	}

?>

<?php if ( $is_home ) : ?>
	
	<?php

	// query arguments
	$query_args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => 'carousel',
			),
		),
	);

	// do the query
	$the_essence_query = new WP_Query( $query_args );

	// vars ( used in the template )
	$count = 0;
	$real_count = 0;
	$post_columns = 3;
	$post_class_append = 'carousel-item ';

	?>

	<?php if ( $the_essence_query->have_posts() ) : ?>

		<div class="carousel-wrapper vertical-carousel-wrapper">
			
			<div class="wrapper">

				<div class="carousel vertical-carousel clearfix" data-pagination="false">
				
					<?php while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); $count++; $real_count++; ?>

						<?php include( locate_template( 'template-parts/listing/blog-post-s3.php' ) ); ?>

					<?php endwhile; ?>

				</div><!-- .vertical-carousel -->

				<span class="carousel-nav-prev"><span class="fa fa-angle-left"></span></span>
				<span class="carousel-nav-next"><span class="fa fa-angle-right"></span></span>

			</div><!-- .wrapper -->

		</div><!-- .vertical-carousel-wrapper -->

	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
	
<?php endif; ?>