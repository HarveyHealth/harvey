<?php get_header(); ?>

	<div id="content" class="col col-8">

		<?php 

			// if there are posts
			if ( have_posts() ) :

				?><div class="blog-posts-listing"><?php

					?><div class="blog-posts-listing-inner clearfix"><?php

						// loop posts
						while ( have_posts() ) : the_post();

							// output from template
							get_template_part( 'template-parts/listing/blog-post-s1' );

						endwhile;

					?></div><!-- .blog-posts-listing-inner --><?php

					// post pagination
					the_essence_posts_pagination( array( 'type' => the_essence_get_theme_mod( 'pagination_type', 'loadmore' ) ) );

				?></div><!-- .blog-posts-listing --><?php

			// if no posts found
			else :

				// output from template
				get_template_part( 'template-parts/content', 'none' );

			// finish if posts
			endif; 

		?>

	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer();
