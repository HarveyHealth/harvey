<?php get_header(); ?>
	
	<?php
		$archive_style = the_essence_get_theme_mod( 'archive_style', '1_cs_12' );
		$archive_style_string = $archive_style;
		$archive_style = explode( '_', $archive_style );

		// has sidebar?
		$has_sidebar = true;
		if ( $archive_style[1] == 'fc' ) {
			$has_sidebar = false;
		}
	?>

	<div id="content" class="<?php if ( $has_sidebar ) echo 'col col-8'; ?>">

		<?php

			// get the title
			$title = 'archives';
			if ( is_category() ) {
				$title = single_cat_title( '', false );
			} elseif ( is_tag() ) {
				$title = single_tag_title( '', false );
			} elseif ( is_author() ) {
				$title = get_the_author();
			} elseif ( is_year() ) {
				$title = get_the_date( 'Y' );
			} elseif ( is_month() ) {
				$title = get_the_date( 'F Y' );
			} elseif ( is_day() ) {
				$title = get_the_date( 'F j, Y' );
			} elseif ( is_post_type_archive() ) {
				$title = post_type_archive_title( '', false );
			} elseif ( is_tax() ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				$title = $tax->labels->singular_name . ' ' . single_term_title( '', false );
			}

		?>

		<h2 class="section-heading-bg pink"><span><span class="no-caps"><?php esc_html_e( 'Currently Browsing:', 'the-essence' ); ?></span> <?php echo esc_html( $title ); ?></span></h2>

		<?php 

			// post style
			$post_style = $archive_style[0];

			// post columns
			$post_columns = $archive_style[2];

			// if there are posts
			if ( have_posts() ) :

				?><div class="blog-posts-listing blog-posts-listing-<?php echo $archive_style_string; ?>"><?php

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
