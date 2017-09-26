<?php get_header(); ?>
	
	<?php
		$search_style = the_essence_get_theme_mod( 'search_style', '1_cs_12' );
		$search_style_string = $search_style;
		$search_style = explode( '_', $search_style );

		// has sidebar?
		$has_sidebar = true;
		if ( $search_style[1] == 'fc' ) {
			$has_sidebar = false;
		}
	?>
	
	<div id="content" class="<?php if ( $has_sidebar ) echo 'col col-8'; ?>">

		<h2 class="section-heading-bg pink"><span><span class="no-caps"><?php esc_html_e( 'Search Results For:', 'the-essence' ); ?></span> <?php echo get_search_query(); ?></span></h2>

		<?php 

			// post style
			$post_style = $search_style[0];

			// post columns
			$post_columns = $search_style[2];

			// if there are posts
			if ( have_posts() ) :

				?><div class="blog-posts-listing blog-posts-listing-<?php echo $search_style_string; ?>"><?php

					?><div class="blog-posts-listing-inner clearfix"><?php

						// count
						$count = 0;
						$real_count = 0;

						// loop posts
						while ( have_posts() ) : the_post(); $count++; $real_count++;

							include( locate_template( 'template-parts/listing/blog-post-s' . $post_style . '.php' ) );

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

	<?php if ( $has_sidebar ) get_sidebar(); ?>

<?php get_footer();
