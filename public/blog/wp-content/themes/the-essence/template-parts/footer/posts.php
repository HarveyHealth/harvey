<?php if ( the_essence_get_theme_mod( 'footer_posts', 'enabled' ) == 'enabled' ) : ?>

	<div id="footer-posts" data-mtst-selector="#footer-posts" data-mtst-label="Footer Posts" data-mtst-no-support="typogrpahy,border">

		<div class="wrapper">

			<h4 class="section-heading" data-mtst-selector="#footer-posts .section-heading" data-mtst-label="Footer Posts - Heading" data-mtst-no-support="background,border"><?php echo esc_html_e( 'Popular Posts', 'the-essence' ); ?></h4>

			<?php

				$posts_category = the_essence_get_theme_mod( 'footer_posts_category', false );
				$posts_order_by = the_essence_get_theme_mod( 'footer_posts_order_by', 'comment_count' );
				$posts_order = the_essence_get_theme_mod( 'footer_posts_order', 'DESC' );
				$posts_amount = the_essence_get_theme_mod( 'footer_posts_amount', 12 );

				// query arguments
				$query_args = array(
					'post_type' => 'post',
					'orderby' => $posts_order_by,
					'order' => $posts_order,
					'posts_per_page' => $posts_amount,
				);

				if ( $posts_category ) {
					$posts_category = explode( ',', $posts_category );
					$query_args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => $posts_category,
						),
					);
				}

				// do the query
				$the_essence_query = new WP_Query( $query_args );

				// vars ( passed on to tempalte )
				$count = 0;
				$real_count = 0;
				$post_columns = 3;
				$post_class_append = 'carousel-item ';

			?>

			<?php if ( $the_essence_query->have_posts() ) : ?>

				<div class="carousel-wrapper">

					<div class="carousel clearfix" data-pagination="false" data-items="4">

						<?php while ( $the_essence_query->have_posts() ) : $the_essence_query->the_post(); $count++; $real_count++; ?>

							<?php include( locate_template( 'template-parts/listing/blog-post-s5.php' ) ); ?>

						<?php endwhile; ?>

					</div><!-- .carousel -->

					<span class="carousel-nav-prev"><span class="fa fa-angle-left"></span></span>
					<span class="carousel-nav-next"><span class="fa fa-angle-right"></span></span>

				</div><!-- .carousel-wrapper -->

			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div><!-- .wrapper -->

	</div><!-- #footer-posts -->

<?php endif; ?>